<?php

namespace frostcheat\essentials\events;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerChangeGodEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    private bool $god;
    private CommandSender $sender;

    public function __construct(Player $player, CommandSender $sender, bool $god) {
        $this->player = $player;
        $this->sender = $sender;
        $this->god = $god;
    }

    public function getCommandSender(): CommandSender {
        return $this->sender;
    }

    public function setCommandSender(CommandSender $sender): void {
        $this->sender = $sender;
    }

    public function getGod(): bool {
        return $this->god;
    }

    public function setGod(bool $god): void {
        $this->god = $god;
    }
}