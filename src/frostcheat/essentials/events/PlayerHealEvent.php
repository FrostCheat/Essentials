<?php

namespace frostcheat\essentials\events;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerHealEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    private float $health;
    private CommandSender $sender;

    public function __construct(Player $player, CommandSender $sender, float $health) {
        $this->player = $player;
        $this->sender = $sender;
        $this->health = $health;
    }

    public function getCommandSender(): CommandSender {
        return $this->sender;
    }

    public function setCommandSender(CommandSender $sender): void {
        $this->sender = $sender;
    }

    public function getHealth(): float {
        return $this->health;
    }

    public function setHealth(float $health): void {
        $this->health = $health;
    }
}