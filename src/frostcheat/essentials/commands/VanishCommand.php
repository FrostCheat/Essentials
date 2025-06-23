<?php

namespace frostcheat\essentials\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\PlayerChangeVanishEvent;
use frostcheat\essentials\Loader;
use frostcheat\essentials\sessions\SessionManager;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class VanishCommand extends BaseCommand {
    public function __construct() {
        parent::__construct(Loader::getInstance(), "vanish", "Enable/Disable vanish mode" , ["v"]);
        $this->setPermission("essentials.command.vanish");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (isset($args["player"])) {
            $p = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($p instanceof Player) {
                if ($sender->hasPermission("essentials.command.vanish.other")) {
                    $this->changeVanish($p, $sender);
                } else {
                    $sender->sendMessage(TextFormat::colorize("&cYou do not have permission to activate vanish mode for other people."));
                }
            } else {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online."));
            }
            return;
        }

        if ($sender instanceof Player) {
            $this->changeVanish($sender, $sender);
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /vanish <player>"));
        }
    }

    public function changeVanish(Player $player, CommandSender $sender): void {
        $session = SessionManager::getInstance()->getSession($player->getName());
        if ($session === null) {
            $sender->sendMessage(TextFormat::colorize("&cError loading session..."));
            return;
        }

        $event = new PlayerChangeVanishEvent($player, $sender, !$session->isVanished());
        $event->call();
        
        if ($event->isCancelled()) return;
        
        $session->setVanished($event->getVanished());
        $str = $event->getVanished() ? "&aon" : "&coff";

        if ($player->getName() === $sender->getName()) {
            $player->sendMessage(TextFormat::colorize("&6Turned $str&6 vanish mode."));
        } else {
            $sender->sendMessage(TextFormat::colorize("&6Turned $str&6 vanish mode for &c{$player->getName()}&6."));
            $player->sendMessage(TextFormat::colorize("&6Your vanish has been turned $str&6."));
        }
    }
}