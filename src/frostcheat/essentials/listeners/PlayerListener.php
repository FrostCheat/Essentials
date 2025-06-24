<?php

namespace frostcheat\essentials\listeners;

use frostcheat\essentials\sessions\SessionManager;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerListener implements Listener {

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
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