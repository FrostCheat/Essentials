<?php

namespace frostcheat\essentials;

use frostcheat\essentials\sessions\Session;
use frostcheat\essentials\sessions\SessionManager;
use frostcheat\essentials\utils\ReflectionUtils;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\player\Player;
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
        }
        
        if (($nick = $session->getNick()) !== null) {
            $player->setDisplayName($nick);
        }
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        if ((bool) Loader::getInstance()->getConfig()->get("showCoordinates", true)) {
            $pk = GameRulesChangedPacket::create(['showCoordinates' => new BoolGameRule(true, false)]);
            $player->getNetworkSession()->sendDataPacket($pk);
        }

        foreach (SessionManager::getInstance()->getAll() as $session) {
            $target = $session->getPlayer();
    
            if (
                $target !== null &&
                $target->getName() !== $player->getName() &&
                $session->isVanished()
            ) {
                $player->hidePlayer($target);
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        $session = SessionManager::getInstance()->getSession($player->getName());
        if ($session !== null && $session->isVanished()) {
            $session->setVanished(false);
        }
    }

    public function onTeleport(EntityTeleportEvent $event): void {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            $session = SessionManager::getInstance()->getSession($entity->getName());
            if ($session !== null) {
                $session->setLastPosition($event->getFrom());
            }
        }
    }
}