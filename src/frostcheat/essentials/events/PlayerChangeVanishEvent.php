<?php

namespace frostcheat\essentials\events;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerChangeVanishEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    private bool $vanished;
    private CommandSender $sender;

    public function __construct(Player $player, CommandSender $sender, bool $vanished) {
        $this->player = $player;
        $this->sender = $sender;
        $this->vanished = $vanished;
    }

    public function getCommandSender(): CommandSender {
        return $this->sender;
    }

    public function setCommandSender(CommandSender $sender): void {
        $this->sender = $sender;
    }

    public function getVanished(): bool {
        return $this->vanished;
    }

    public function setVanished(bool $vanished): void {
        $this->vanished = $vanished;
    }
}