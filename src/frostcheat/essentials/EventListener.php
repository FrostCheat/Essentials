<?php

namespace frostcheat\essentials;

use frostcheat\essentials\utils\ReflectionUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\player\PlayerInfo;

class EventListener implements Listener {

    public function onLogin(PlayerPreLoginEvent $event): void {
        ReflectionUtils::setProperty(
            PlayerInfo::class, 
            $event->getPlayerInfo(), 
            "username", 
            str_replace(" ","_", $event->getPlayerInfo()->getUsername())
        );
    }
}