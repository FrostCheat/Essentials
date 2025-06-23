<?php

namespace frostcheat\essentials\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\PlayerHealEvent;
use frostcheat\essentials\Loader;
use frostcheat\essentials\sessions\SessionManager;
use frostcheat\essentials\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class HealCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Loader::getInstance(), "heal", "Restores a player's health");
        $this->setPermission("essentials.command.heal");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (isset($args["player"])) {
            $p = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($p instanceof Player) {
                if ($sender->hasPermission("essentials.command.heal.other")) {
                    $this->heal($p, $sender);
                } else {
                    $sender->sendMessage(TextFormat::colorize("&cYou are not allowed to restore another player's health."));
                }
            } else {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online."));
            }
            return;
        }

        if ($sender instanceof Player) {
            $this->heal($sender, $sender);
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /heal <player>"));
        }
    }

    public function heal(Player $player, CommandSender $sender): void {
        $session = SessionManager::getInstance()->getSession($player->getName()) 
        ?? SessionManager::getInstance()->getSessionByNick($player->getName());
        if ($session !== null) {
            if ($session->getCooldown("heal") > time() && !$sender->hasPermission("essentials.command.heal.bypass")) {
                $sender->sendMessage(TextFormat::colorize("&cYou must wait " . Utils::date($session->getCooldown("heal") - time()) . " to run this command again."));
                return;
            }
        }

        $event = new PlayerHealEvent($player, $sender, $player->getMaxHealth());
        $event->call();

        if ($event->isCancelled()) return;

        if (!$sender->hasPermission("essentials.command.heal.bypass")) {
            $session->addCooldown("heal", time() + (int) Loader::getInstance()->getConfig()->get("heal.cooldown", 300));
        }

        $player->setHealth($event->getHealth());
        if ($player->getName() === $sender->getName()) {
            $sender->sendMessage(TextFormat::colorize("&6You have been healed."));
        } else {
            $sender->sendMessage(TextFormat::colorize("&6Restored &c{$player->getName()}&r&6's health."));
            $player->sendMessage(TextFormat::colorize("&6You have been healed."));
        }
    }
}