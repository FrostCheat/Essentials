<?php

namespace frostcheat\essentials\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\PlayerChangeGodEvent;
use frostcheat\essentials\Loader;
use frostcheat\essentials\sessions\SessionManager;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class GodCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Loader::getInstance(), "god", "Enable/Disable god mode");
        $this->setPermission("essentials.command.god");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (isset($args["player"])) {
            $p = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($p instanceof Player) {
                if ($sender->hasPermission("essentials.command.god.other")) {
                    $this->changeGod($p, $sender);
                } else {
                    $sender->sendMessage(TextFormat::colorize("&cYou do not have permission to activate god mode for other people."));
                }
            } else {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online."));
            }
            return;
        }

        if ($sender instanceof Player) {
            $this->changeGod($sender, $sender);
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /god <player>"));
        }
    }

    public function changeGod(Player $player, CommandSender $sender): void {
        $session = SessionManager::getInstance()->getSession($player->getName());
        if ($session === null) {
            $sender->sendMessage(TextFormat::colorize("&cError loading session..."));
            return;
        }

        $event = new PlayerChangeGodEvent($player, $sender, !$session->isGodMode());
        $event->call();
        
        if ($event->isCancelled()) return;
        
        $session->setGodMode($event->getGod());
        $str = $event->getGod() ? "&aon" : "&coff";

        if ($player->getName() === $sender->getName()) {
            $player->sendMessage(TextFormat::colorize("&6Turned $str&6 god mode."));
        } else {
            $sender->sendMessage(TextFormat::colorize("&6Turned $str&6 god mode for &c{$player->getName()}&6."));
            $player->sendMessage(TextFormat::colorize("&6Your god has been turned $str&6."));
        }
    }
}