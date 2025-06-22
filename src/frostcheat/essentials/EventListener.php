<?php

namespace frostcheat\essentials;

use frostcheat\essentials\sessions\Session;
use frostcheat\essentials\sessions\SessionManager;
use frostcheat\essentials\utils\ReflectionUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\player\PlayerInfo;

class EventListener implements Listener {

    public function onPreLogin(PlayerPreLoginEvent $event): void {
        ReflectionUtils::setProperty(
            PlayerInfo::class, 
            $event->getPlayerInfo(), 
            "username", 
            str_replace(" ","_", $event->getPlayerInfo()->getUsername())
        );
    }

    public function onLogin(PlayerLoginEvent $event): void {
        $player = $event->getPlayer();
        $session = SessionManager::getInstance()->getSession($player->getName());

        if ($session === null) {
            SessionManager::getInstance()->addSession(new Session($player->getName()));
        } else {
            if ($session->getName() !== $player->getName()) {
                $session->setName($player->getName());
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        if ((bool) Loader::getInstance()->getConfig()->get("showCoordinates", true)) {
            $pk = GameRulesChangedPacket::create(['showCoordinates' => new BoolGameRule(true, false)]);
            $player->getNetworkSession()->sendDataPacket($pk);
        }
    }
}