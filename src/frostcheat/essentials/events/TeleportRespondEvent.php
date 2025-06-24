<?php

namespace frostcheat\essentials\events;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class TeleportRespondEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    protected bool $accept;

    public function __construct(Player $player, bool $accept) {
        $this->player = $player;
        $this->accept = $accept;
    }

    public function isAccepted(): bool {
        return $this->accept;
    }

    public function setAccept(bool $accept): void {
        $this->accept = $accept;
    }
}