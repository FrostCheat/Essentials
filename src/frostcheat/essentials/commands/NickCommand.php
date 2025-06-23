<?php

namespace frostcheat\essentials\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\PlayerChangeNickEvent;
use frostcheat\essentials\Loader;
use frostcheat\essentials\sessions\SessionManager;
use frostcheat\essentials\utils\ReflectionUtils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class NickCommand extends BaseCommand {
    public function __construct() {
        parent::__construct(Loader::getInstance(), "nick", "Change a player's name");
        $this->setPermission("essentials.command.nick");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("nick"));
        $this->registerArgument(1, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        $nick = $args["nick"];
        if (!Player::isValidUserName($nick) ||
            str_contains($nick, " ") ||
            SessionManager::getInstance()->getSession($nick) !== null
            ) {
                $sender->sendMessage(TextFormat::colorize("&cThat nickname is not valid"));
                return;
            }


        if (isset($args["player"])) {
            $p = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($p instanceof Player) {
                if ($sender->hasPermission("essentials.command.nick.other")) {
                    $this->changeNick($p, $sender, $nick);
                } else {
                    $sender->sendMessage(TextFormat::colorize("&cYou do not have permission to change nickname for other people."));
                }
            } else {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online."));
            }
            return;
        }

        if ($sender instanceof Player) {
            $this->changeNick($sender, $sender, $nick);
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /nick <player>"));
        }
    }

    public function changeNick(Player $player, CommandSender $sender, string $nick): void {
        if ($player->getName() === $nick) {
            $player->sendMessage(TextFormat::colorize("&cThe user already has that name, try another one."));
            return;
        }

        $session = SessionManager::getInstance()->getSession($player->getName());
        if ($session === null) {
            $sender->sendMessage(TextFormat::colorize("&cError loading session..."));
            return;
        }

        if ($nick === "reset") {
            $event = new PlayerChangeNickEvent($player, $sender, $session->getName());
            $event->call();

            if ($event->isCancelled()) return;

            $isSelf = $player->getName() === $sender->getName();

            if (($isSelf && $sender->hasPermission("essentials.command.nick.reset")) ||
            (!$isSelf && $sender->hasPermission("essentials.command.nick.reset.other"))) {
                $player->setDisplayName($event->getNick());
                $session->setNick(null);
                $sender->sendMessage(TextFormat::colorize("{$player->getName()}'s nickname has been successfully reset"));
            } else {
                $sender->sendMessage(TextFormat::colorize("&cYou don't have permission to reset this nickname."));
            }
            return;
        }


        $event = new PlayerChangeNickEvent($player, $sender, $nick);
        $event->call();

        if ($event->isCancelled()) return;

        $player->setDisplayName($event->getNick());
        $session->setNick($event->getNick());
        if ($player->getName() === $sender->getName()) {
            $sender->sendMessage(TextFormat::colorize("&6Your nick has been set to &c{$event->getNick()}&r&6."));
        } else {
            $sender->sendMessage(TextFormat::colorize("&6Set {$player->getName()}&6's nick to &r&c{$event->getNick()}&r&6."));
            $player->sendMessage(TextFormat::colorize("&6Your nick has been set to &c{$event->getNick()}&r&6."));
        }
        $sender->sendMessage(TextFormat::colorize("&eTo return to a player's real nickname you must do /nick reset or /nick reset <player>"));
    }
}