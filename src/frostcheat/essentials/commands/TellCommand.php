<?php

namespace frostcheat\essentials\commands;

use frostcheat\essentials\events\PlayerPrivateMessageEvent;
use frostcheat\essentials\Loader;
use frostcheat\essentials\sessions\SessionManager;
use frostcheat\essentials\utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class TellCommand extends Command {
    public function __construct() {
        parent::__construct("tell", "Sends a private message", "/msg <player> <msg>", ["w", "msg", "message"]);
        $this->setPermission("essentials.command.tell");
    }

    public function execute(CommandSender $sender, string $aliasUsed, array $args): void {
        $target = Loader::getInstance()->getServer()->getPlayerExact($args[0]);
        if ($target === null) {
            $sender->sendMessage(TextFormat::colorize("&cThis player is not online."));
            return;
        }

        unset($args[0]);
        if (count($args) <= 0) {
            $sender->sendMessage(TextFormat::colorize("&cPlease enter a message."));
            return;
        }
        $str = implode(" ", $args);

        if ($sender instanceof Player) {
            $event = new PlayerPrivateMessageEvent($sender, $str, $target);
            $event->call();

            if ($event->isCancelled()) return;
        }

        $session = SessionManager::getInstance()->getSession($target->getName());
        if ($session !== null) {
            $session->setLastPlayer($sender->getName());
        }

        $sender->sendMessage(TextFormat::colorize("&7(To &g{$target->getName()}&7) &e$str"));
        $target->sendMessage(TextFormat::colorize("&7(From &g{$sender->getName()}&7) &e$str"));
        Utils::play($target, "random.levelup");

        if ((bool) Loader::getInstance()->getConfig()->get("tell-log", true)) {
            Loader::getInstance()->getLogger()->info(TextFormat::colorize("&4[SPY] &7(From &g{$sender->getName()} &7to &g{$target->getName()}&7) &e$str"));
            foreach (Loader::getInstance()->getServer()->getOnlinePlayers() as $online) {
                if ($online->hasPermission("essentials.tell.see")) {
                    $online->sendMessage(TextFormat::colorize("&4[SPY] &7(From &g{$sender->getName()} &7to &g{$target->getName()}&7) &e$str"));
                }
            }
        }
    }
}