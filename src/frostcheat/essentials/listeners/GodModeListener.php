<?php

namespace frostcheat\essentials\listeners;

use frostcheat\essentials\sessions\SessionManager;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;

class GodModeListener implements Listener {

    public function onDamage(EntityDamageEvent $event): void {
        $player = $event->getEntity();
        
        if (
            $player instanceof Player && 
            ($session = SessionManager::getInstance()->getSession($player->getName())) !== null && 
            $session->isGodMode()
        ) {
            $event->cancel();
        }
    }
    public function onExhaust(PlayerExhaustEvent $event): void {
        $player = $event->getEntity();
        if (
            $player instanceof Player && 
            ($session = SessionManager::getInstance()->getSession($player->getName())) !== null && 
            $session->isGodMode()
        ) {
            $event->cancel();
        }
    }

}