<?php

namespace frostcheat\essentials;

use frostcheat\essentials\sessions\Session;
use frostcheat\essentials\sessions\SessionManager;
use frostcheat\essentials\utils\ReflectionUtils;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\player\Player;
use pocketmine\player\PlayerInfo;

class EventListener implements Listener {

    public function onPreLogin(PlayerPreLoginEvent $event): void {
        $session = SessionManager::getInstance()->getSession($event->getPlayerInfo()->getUsername()) 
        ?? SessionManager::getInstance()->getSessionByNick($event->getPlayerInfo()->getUsername());
        
        if ($session === null) {
            $session = new Session(str_replace(" ","_", $event->getPlayerInfo()->getUsername()));
            SessionManager::getInstance()->addSession($session);
        }
        
        if (($nick = $session->getNick()) !== null) {
            ReflectionUtils::setProperty(PlayerInfo::class, $event->getPlayerInfo(), "username", $nick);
        } else {
            ReflectionUtils::setProperty(
                PlayerInfo::class, 
                $event->getPlayerInfo(), 
                "username", 
                str_replace(" ","_", $event->getPlayerInfo()->getUsername())
            );
        }
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        if ((bool) Loader::getInstance()->getConfig()->get("showCoordinates", true)) {
            $pk = GameRulesChangedPacket::create(['showCoordinates' => new BoolGameRule(true, false)]);
            $player->getNetworkSession()->sendDataPacket($pk);
        }
    }

    public function onTeleport(EntityTeleportEvent $event): void {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            $session = SessionManager::getInstance()->getSession($entity->getName()) ?? SessionManager::getInstance()->getSessionByNick($entity->getName());
            if ($session !== null) {
                $session->setLastPosition($event->getFrom());
            }
        }
    }
}