<?php

namespace frostcheat\essentials\commands\fix\subcommands;

use frostcheat\essentials\libs\CortexPE\Commando\args\RawStringArgument;
use frostcheat\essentials\libs\CortexPE\Commando\BaseSubCommand;
use frostcheat\essentials\events\PlayerFixEvent;
use frostcheat\essentials\Loader;
use frostcheat\essentials\utils\CommandManager;
use pocketmine\command\CommandSender;
use pocketmine\item\Durable;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class AllSubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct("all", "Fixs the all items in the inventory");
        $this->setPermission("essentials.command.fix.all");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (isset($args["player"])) {
            if (!$sender->hasPermission("essentials.command.fix.all.other")) {
                $sender->sendMessage(TextFormat::colorize("&cYou dont have permission to fix all items for other people."));
                return;
            }

            $target = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($target === null) {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online."));
                return;
            }

            $this->repair($target, $sender, $target->getInventory()->getContents());
            return;
        }

        if ($sender instanceof Player) {
            $this->repair($sender, $sender, $sender->getInventory()->getContents());
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /fix all <player>"));
        }
    }

    public function repair(Player $player, CommandSender $sender, array $items): void {
        foreach ($items as $slot => $item) {
            if ($item instanceof Durable) {
                $item->setDamage(0);
            }
        }

        $event = new PlayerFixEvent($player, $sender, $items);
        $event->call();

        if ($event->isCancelled()) return;

        $player->getInventory()->setContents($event->getItems());

        if ($player->getName() === $sender->getName()) {
            $player->sendMessage(TextFormat::colorize("&6Successfully fixed all items."));
        } else {
            $sender->sendMessage(TextFormat::colorize("&6Successfully fixed all items for &c{$player->getName()}&6."));
            $player->sendMessage(TextFormat::colorize("&6Successfully fixed all items."));
        }
    }
}