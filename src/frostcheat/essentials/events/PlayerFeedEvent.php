<?php

namespace frostcheat\essentials\events;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerFeedEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    private float $food;
    private CommandSender $sender;

    public function __construct(Player $player, CommandSender $sender, float $food) {
        $this->player = $player;
        $this->sender = $sender;
        $this->food = $food;
    }

    public function getCommandSender(): CommandSender {
        return $this->sender;
    }

    public function setCommandSender(CommandSender $sender): void {
        $this->sender = $sender;
    }

    public function getFood(): float {
        return $this->food;
    }

    public function setFood(float $food): void {
        $this->food = $food;
    }
}