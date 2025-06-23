<?php

namespace frostcheat\essentials\commands;

use frostcheat\essentials\events\PlayerPrivateMessageEvent;
use frostcheat\essentials\Loader;
use frostcheat\essentials\sessions\SessionManager;
use frostcheat\essentials\utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class ReplyCommand extends Command {
    public function __construct() {
        parent::__construct("reply", "Reply to the last private message", "/r <msg>", ["r"]);
        $this->setPermission("essentials.command.reply");
    }

    public function execute(CommandSender $sender, string $aliasUsed, array $args): void {
        $session = SessionManager::getInstance()->getSession($sender->getName());
        if ($session === null || ($session !== null && $session->getLastPlayer() === null)) {
            $sender->sendMessage(TextFormat::colorize("&cNo last player to reply to."));
            return;
        }

        $target = Loader::getInstance()->getServer()->getPlayerExact($session->getLastPlayer());
        if ($target === null) {
            $sender->sendMessage(TextFormat::colorize("&cPlayer is offline."));
            return;
        }

        if (count($args) <= 0) {
            $sender->sendMessage(TextFormat::colorize("&cPlease enter a message."));
            return;
        }
        $str = implode(" ", $args);

        $event = new PlayerPrivateMessageEvent($sender, $str, $target);
        $event->call();

        if ($event->isCancelled()) return;

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