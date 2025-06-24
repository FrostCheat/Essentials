<?php

namespace frostcheat\essentials\events;

use frostcheat\essentials\sessions\TeleportRequest;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class TeleportRequestEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    protected TeleportRequest $request;
    protected Player $sender;

    public function __construct(Player $player, Player $sender, TeleportRequest $request) {
        $this->player = $player;
        $this->sender = $sender;
        $this->request = $request;
    }

    public function getRequest(): TeleportRequest {
        return $this->request;
    }

    public function setRequest(TeleportRequest $request): void {
        $this->request = $request;
    }

    public function getSender(): Player {
        return $this->sender;
    }

    public function setSender(Player $sender): void {
        $this->sender = $sender;
    }
}