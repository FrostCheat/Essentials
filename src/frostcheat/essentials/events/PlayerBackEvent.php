<?php

namespace frostcheat\essentials\events;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;
use pocketmine\world\Position;

class PlayerBackEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    private $position;

    public function __construct(Player $player, Position $position){
        $this->player = $player;
        $this->position = $position;
    }

    public function getPosition() : Position{
        return $this->position;
    }

    public function setPosition(Position $position) : void{
        $this->position = $position;
    }
}