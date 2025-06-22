<?php

namespace frostcheat\essentials\events;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerClearInventoryEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;
    private CommandSender $sender;

    public function __construct(Player $player, CommandSender $sender) {
        $this->player = $player;
        $this->sender = $sender;
    }

    public function getCommandSender(): CommandSender {
        return $this->sender;
    }

    public function setCommandSender(CommandSender $sender): void {
        $this->sender = $sender;
    }
}