<?php

namespace frostcheat\essentials\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\PlayerClearInventoryEvent;
use frostcheat\essentials\Loader;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class ClearCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Loader::getInstance(), "clear", "Clear inventory");
        $this->setPermission("essentials.command.clear");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (isset($args["player"])) {
            $p = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($p instanceof Player) {
                if ($sender->hasPermission("essentials.command.clear.other")) {
                    $this->clear($p, $sender);
                } else {
                    $sender->sendMessage(TextFormat::colorize("&cYou are not allowed to empty other people's inventory."));
                }
            } else {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online."));
            }
            return;
        }

        if ($sender instanceof Player) {
            $this->clear($sender, $sender);
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /clear <player>"));
        }
    }

    public function clear(Player $player, CommandSender $sender): void {
        $event = new PlayerClearInventoryEvent($player, $sender);
        $event->call();

        if ($event->isCancelled()) return;

        $player->getArmorInventory()->clearAll();
        $player->getInventory()->clearAll();

        if ($player->getName() === $sender->getName()) {
            $sender->sendMessage(TextFormat::colorize("&6You cleared your inventory."));
        } else {
            $sender->sendMessage(TextFormat::colorize("&6Cleared {$player->getName()}&6's inventory."));
            $player->sendMessage(TextFormat::colorize("&6Your inventory has been cleared."));
        }
    }
}