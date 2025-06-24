<?php

namespace frostcheat\essentials\events;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerBurnEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    private CommandSender $sender;
    private int $time;

    public function __construct(Player $player, CommandSender $sender, int $time) {
        $this->player = $player;
        $this->sender = $sender;
        $this->time = $time;
    }

    public function getSender(): Player {
        return $this->sender;
    }

    public function setSender(Player $sender): void {
        $this->sender = $sender;
    }

    public function getTime(): int {
        return $this->time;
    }

    public function setTime(int $time): void {
        $this->time = $time;
    }
}