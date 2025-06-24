<?php

namespace frostcheat\essentials\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\PlayerMilkEvent;
use frostcheat\essentials\Loader;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class MilkCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Loader::getInstance(), "milk", "Removes effects from a player");
        $this->setPermission("essentials.command.milk");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (isset($args["player"])) {
            $p = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($p instanceof Player) {
                if ($sender->hasPermission("essentials.command.milk.other")) {
                    $this->clearEffects($p, $sender);
                } else {
                    $sender->sendMessage(TextFormat::colorize("&cYou do not have permission to clear effects for other people."));
                }
            } else {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online."));
            }
            return;
        }

        if ($sender instanceof Player) {
            $this->clearEffects($sender, $sender);
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /milk <player>"));
        }
    }

    public function clearEffects(Player $player, CommandSender $sender): void {
        $event = new PlayerMilkEvent($player, $sender);
        $event->call();
        
        if ($event->isCancelled()) return;
        
        $player->getEffects()->clear();

        if ($player->getName() === $sender->getName()) {
            $sender->sendMessage(TextFormat::colorize("&6Cleared all effects."));
        } else {
            $player->sendMessage(TextFormat::colorize("&6Cleared all effects."));
            $sender->sendMessage(TextFormat::colorize("&6Cleared all &c{$player->getName()}&6's effects."));
        }
    }
}