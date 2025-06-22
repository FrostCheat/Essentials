<?php

namespace frostcheat\essentials\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\PlayerChangeFlyEvent;
use frostcheat\essentials\Loader;
use pocketmine\command\CommandSender;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class FlyCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Loader::getInstance(), "fly", "Enable/Disable fly mode");
        $this->setPermission("essentials.command.fly");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (isset($args["player"])) {
            $p = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($p instanceof Player) {
                $this->changeFly($p, $sender);
            } else {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online"));
            }
            return;
        }

        if ($sender instanceof Player) {
            $this->changeFly($sender, $sender);
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /fly <player>"));
        }
    }

    public function changeFly(Player $player, CommandSender $sender): void {
        if ($player->getGamemode() === GameMode::CREATIVE() || $player->getGamemode() === GameMode::SPECTATOR()) {
            $player->sendMessage(TextFormat::colorize("&cYou can't turn flight mode on/off in {$player->getGamemode()->name}."));
            return;
        }

        $event = new PlayerChangeFlyEvent($player, $sender, !$player->getAllowFlight());
        $event->call();
        
        if ($event->isCancelled()) return;
        
        $player->setAllowFlight($event->getFlight());
        $player->setFlying($event->getFlight());
        $str = $event->getFlight() ? "&aon" : "&coff";

        if ($player->getName() === $sender->getName()) {
            $player->sendMessage(TextFormat::colorize("&6Turned $str&6 flight mode."));
        } else {
            $sender->sendMessage(TextFormat::colorize("&6Turned $str&6 flight mode for &c{$player->getName()}&6."));
            $player->sendMessage(TextFormat::colorize("&6Your flight has been turned $str&6."));
        }
    }
}