<?php

namespace frostcheat\essentials\events;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\server\ServerEvent;

class ServerBroadcastEvent extends ServerEvent implements Cancellable {
    use CancellableTrait;

    protected $message;
    protected $player;

    public function __construct(CommandSender $player, string $message) {
        $this->player = $player;
        $this->message = $message;
    }

    public function getPlayer(): CommandSender {
        return $this->player;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message): void {
        $this->message = $message;
    }
}