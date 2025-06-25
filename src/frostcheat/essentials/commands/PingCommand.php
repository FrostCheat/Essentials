<?php

namespace frostcheat\essentials\commands;

use frostcheat\essentials\libs\CortexPE\Commando\args\RawStringArgument;
use frostcheat\essentials\libs\CortexPE\Commando\BaseCommand;
use frostcheat\essentials\Loader;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class PingCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Loader::getInstance(), "ping", "Get your ping or others ping");
        $this->setPermission("essentials.command.ping");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (isset($args["player"])) {
            $p = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($p instanceof Player) {
                if ($sender->hasPermission("essentials.command.ping.other")) {
                    $sender->sendMessage(TextFormat::colorize("&e{$p->getName()}'s Ping: &a{$p->getNetworkSession()->getPing()}"));
                } else {
                    $sender->sendMessage(TextFormat::colorize("&cYou do not have permission to see other players' ping."));
                }
            } else {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online"));
            }
            return;
        }

        if ($sender instanceof Player) {
            $sender->sendMessage(TextFormat::colorize("&eYour Ping: &a{$sender->getNetworkSession()->getPing()}"));
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /ping <player>"));
        }
    }
}