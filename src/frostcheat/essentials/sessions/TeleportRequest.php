<?php

namespace frostcheat\essentials\sessions;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class TeleportRequest {
    private Player $target;
    private Player $sender;
    private int $time;
    private bool $here;

    public function __construct(Player $target, Player $sender, int $time, bool $here) {
        $this->target = $target;
        $this->sender = $sender;
        $this->time = $time;
        $this->here = $here;
    }

    public function getTarget(): Player {
        return $this->target;
    }

    public function getSender(): Player {
        return $this->sender;
    }

    public function getTime(): int {
        return $this->time;
    }

    public function isHere(): bool {
        return $this->here;
    }

    public function isExpired(): bool {
        return $this->time < time();
    }

    public function teleport() : void{
        if (!$this->target->isOnline() || !$this->target->isOnline()) return;
        if ($this->isHere()) {
            $this->target->sendMessage(TextFormat::colorize("&6Teleporting..."));
            $this->target->teleport($this->sender->getPosition());
        } else {
            $this->sender->teleport($this->target->getPosition());
            $this->sender->sendMessage(TextFormat::colorize("&6Teleporting..."));
        }
    }
}