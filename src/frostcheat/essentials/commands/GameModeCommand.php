<?php

namespace frostcheat\essentials\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\commands\args\GameModeArgument;
use frostcheat\essentials\Loader;
use pocketmine\command\CommandSender;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class GameModeCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Loader::getInstance(), "gamemode", "Change a player's game mode", ["gm"]);
        $this->setPermission("essentials.command.gamemode");
    }

    public function prepare(): void {
        $this->registerArgument(0, new GameModeArgument("gamemode"));
        $this->registerArgument(1, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        $gameMode = $args["gamemode"];
        if (isset($args["player"])) {
            $p = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($p instanceof Player) {
                if ($sender->hasPermission("essentials.command.gamemode.other")) {
                    $this->changeGamemode($p, $sender, $gameMode);
                } else {
                    $sender->sendMessage(TextFormat::colorize("&cYou do not have permission to change game mode for other people."));
                }
            } else {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online."));
            }
            return;
        }

        if ($sender instanceof Player) {
            $this->changeGamemode($sender, $sender, $gameMode);
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /gamemode <player>"));
        }
    }

    public function changeGamemode(Player $player, CommandSender $sender, GameMode $gameMode): void {
        if ($player->getGamemode() === $gameMode) {
            $player->sendMessage(TextFormat::colorize("&cYou can't change your game mode because you already have that game mode."));
            return;
        }

        $player->setGamemode($gameMode);
        if ($player->getName() === $sender->getName()) {
            $player->sendMessage(TextFormat::colorize("&6Your game mode has changed to {$gameMode->name}."));
        } else {
            $sender->sendMessage(TextFormat::colorize("&6Set GameMode ({$gameMode->name}) for &c{$player->getName()}&6."));
            $player->sendMessage(TextFormat::colorize("&6Your game mode has changed to {$gameMode->name}."));
        }
    }
}