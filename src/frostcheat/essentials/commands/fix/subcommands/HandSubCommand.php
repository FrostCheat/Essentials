<?php

namespace frostcheat\essentials\commands\fix\subcommands;

use frostcheat\essentials\libs\CortexPE\Commando\args\RawStringArgument;
use frostcheat\essentials\libs\CortexPE\Commando\BaseSubCommand;
use frostcheat\essentials\events\PlayerFixEvent;
use frostcheat\essentials\Loader;
use frostcheat\essentials\utils\CommandManager;
use pocketmine\command\CommandSender;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class HandSubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct("hand", "Fixs the item in hand");
        $this->setPermission("essentials.command.fix.hand");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (isset($args["player"])) {
            if (!$sender->hasPermission("essentials.command.fix.hand.other")) {
                $sender->sendMessage(TextFormat::colorize("&cYou dont have permission to fix item in hand for other people."));
                return;
            }

            $target = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($target === null) {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online."));
                return;
            }

            $item = $target->getInventory()->getItemInHand();
            $this->repair($target, $sender, $item);
            return;
        }

        if ($sender instanceof Player) {
            $item = $sender->getInventory()->getItemInHand();
            $this->repair($sender, $sender, $item);
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /fix hand <player>"));
        }
    }

    public function repair(Player $player, CommandSender $sender, Item $item): void {
        if ($item === null || !$item instanceof Durable) {
            $sender->sendMessage(TextFormat::colorize("&cItem in hand is not valid"));
            return;
        }

        $item->setDamage(0);
        $event = new PlayerFixEvent($player, $sender, [$item]);
        $event->call();

        if ($event->isCancelled()) return;

        if (isset($event->getItems()[0])) {
            $player->getInventory()->setItemInHand($event->getItems()[0]);

            if ($player->getName() === $sender->getName()) {
                $player->sendMessage(TextFormat::colorize("&6Successfully fixed item in hand."));
            } else {
                $sender->sendMessage(TextFormat::colorize("&6Successfully fixed item in hand for &c{$player->getName()}&6."));
                $player->sendMessage(TextFormat::colorize("&6Successfully fixed item in hand."));
            }
        }
    }
}