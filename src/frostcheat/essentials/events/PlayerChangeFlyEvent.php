<?php

namespace frostcheat\essentials\events;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerChangeFlyEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    private bool $flight;
    private CommandSender $sender;

    public function __construct(Player $player, CommandSender $sender, bool $flight) {
        $this->player = $player;
        $this->sender = $sender;
        $this->flight = $flight;
    }

    public function getCommandSender(): CommandSender {
        return $this->sender;
    }

    public function setCommandSender(CommandSender $sender): void {
        $this->sender = $sender;
    }

    public function getFlight(): bool {
        return $this->flight;
    }

    public function setFlight(bool $flight): void {
        $this->flight = $flight;
    }
}