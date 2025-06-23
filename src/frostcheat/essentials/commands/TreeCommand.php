<?php

namespace frostcheat\essentials\commands;

use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\commands\args\TreeArgument;
use frostcheat\essentials\events\SpawnTreeEvent;
use frostcheat\essentials\Loader;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Random;
use pocketmine\utils\TextFormat;
use pocketmine\world\generator\object\TreeType;
use pocketmine\world\generator\object\TreeFactory;
use pocketmine\world\Position;

class TreeCommand extends BaseCommand {
    public function __construct() {
        parent::__construct(Loader::getInstance(), "tree", "Spawns a tree at your target block");
        $this->setPermission("essentials.command.tree");
    }

    public function prepare(): void {
        $this->registerArgument(0, new TreeArgument("tree"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::colorize("&cYou must be a player to execute this command."));
            return;
        }

        $world = $sender->getWorld();
        $pos = $sender->getPosition();

        /** @var TreeType $tree */
        $treeType = $args["tree"];

        $event = new SpawnTreeEvent($sender, $pos);
        $event->call();

        if ($event->isCancelled()) return;
        
        $random = new Random();
        $tree = TreeFactory::get($random, $treeType);
        if ($tree->canPlaceObject($world, $pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), $random)) {
            $blockTransaction = $tree->getBlockTransaction($world, $pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), $random);
            $blockTransaction?->apply();
            $sender->sendMessage(TextFormat::colorize("&aSpawned tree at &e" . $pos->asVector3()));
        } else {
            $sender->sendMessage(TextFormat::colorize("&cCan't place tree at this location."));
        }
    }
}
