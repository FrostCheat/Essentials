<?php

namespace frostcheat\essentials\events;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;
use pocketmine\world\Position;

class SpawnTreeEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    protected $position;
    protected $bigtree;

    public function __construct(Player $player, Position $position, bool $bigtree = false){
        $this->player = $player;
        $this->position = $position;
        $this->bigtree = $bigtree;
    }

    public function getPosition() : Position{
        return $this->position;
    }

    public function setPosition(Position $position) : void{
        $this->position = $position;
    }

    public function isBigTree() : bool {
        return $this->bigtree;
    }

    public function setBigTree(bool $bigtree = true) : void{
        $this->bigtree = $bigtree;
    }
}