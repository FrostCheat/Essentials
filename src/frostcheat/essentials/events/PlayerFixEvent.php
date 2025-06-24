<?php

namespace frostcheat\essentials\events;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerFixEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    private CommandSender $sender;
    private array $items;

    public function __construct(Player $player, CommandSender $sender, array $items) {
        $this->player = $player;
        $this->sender = $sender;
        $this->items = $items;
    }

    public function getSender(): Player {
        return $this->sender;
    }

    public function setSender(Player $sender): void {
        $this->sender = $sender;
    }

    public function getItems(): array {
        return $this->items;
    }

    public function setItems(array $items): void {
        $this->items = $items;
    }
}