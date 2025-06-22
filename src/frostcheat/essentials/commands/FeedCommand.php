<?php

namespace frostcheat\essentials\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\PlayerFeedEvent;
use frostcheat\essentials\Loader;
use frostcheat\essentials\sessions\SessionManager;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class FeedCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Loader::getInstance(), "feed", "Restores a player's food");
        $this->setPermission("essentials.command.feed");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (isset($args["player"])) {
            $p = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($p instanceof Player) {
                if ($sender->hasPermission("essentials.command.feed.other")) {
                    $this->feed($p, $sender);
                } else {
                    $sender->sendMessage(TextFormat::colorize("&cYou are not allowed to restore another player's food."));
                }
            } else {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online."));
            }
            return;
        }

        if ($sender instanceof Player) {
            $this->feed($sender, $sender);
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /feed <player>"));
        }
    }

    public function feed(Player $player, CommandSender $sender): void {
        $session = SessionManager::getInstance()->getSession($sender->getName());
        if ($session !== null) {
            if ($session->getCooldown("feed") > time() && !$sender->hasPermission("essentials.command.feed.bypass")) {
                $sender->sendMessage(TextFormat::colorize("&cYou must wait " . ($session->getCooldown("feed") - time()) . "s to run this command again."));
                return;
            }
        }

        $event = new PlayerFeedEvent($player, $sender, $player->getHungerManager()->getMaxFood());
        $event->call();

        if ($event->isCancelled()) return;

        if (!$sender->hasPermission("essentials.command.feed.bypass")) {
            $session->addCooldown("feed", time() + (int) Loader::getInstance()->getConfig()->get("feed.cooldown", 120));
        }

        $player->getHungerManager()->setFood($event->getFood());
        if ($player->getName() === $sender->getName()) {
            $sender->sendMessage(TextFormat::colorize("&6You have been fed."));
        } else {
            $sender->sendMessage(TextFormat::colorize("&6Restored &c{$player->getName()}&r&6's food."));
            $player->sendMessage(TextFormat::colorize("&6You have been fed."));
        }
    }
}