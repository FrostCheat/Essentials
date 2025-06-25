<?php

namespace frostcheat\essentials\commands;

use frostcheat\essentials\libs\CortexPE\Commando\args\IntegerArgument;
use frostcheat\essentials\libs\CortexPE\Commando\args\RawStringArgument;
use frostcheat\essentials\libs\CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\PlayerBurnEvent;
use frostcheat\essentials\Loader;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class BurnCommand extends BaseCommand {
    public function __construct() {
        parent::__construct(Loader::getInstance(), "burn", "Set on fire a player");
        $this->setPermission("essentials.command.burn");
    }

    public function prepare(): void {
        $this->registerArgument(0, new IntegerArgument("time"));
        $this->registerArgument(1, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        $time = $args["time"];
        if (isset($args["player"])) {
            $p = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if ($p instanceof Player) {
                if ($sender->hasPermission("essentials.command.burn.other")) {
                    $this->burn($p, $sender, $time);
                } else {
                    $sender->sendMessage(TextFormat::colorize("&cYou do not have permission to burn other people."));
                }
            } else {
                $sender->sendMessage(TextFormat::colorize("&cPlayer is not online."));
            }
            return;
        }

        if ($sender instanceof Player) {
            $this->burn($sender, $sender, $time);
        } else {
            $sender->sendMessage(TextFormat::colorize("&cUsage: /burn <time> <player>"));
        }
    }

    public function burn(Player $player, CommandSender $sender, int $time): void {
        $event = new PlayerBurnEvent($player, $sender, $time);
        $event->call();
        
        if ($event->isCancelled()) return;
        
        $player->setOnFire($event->getTime());

        if ($player->getName() === $sender->getName()) {
            $player->sendMessage(TextFormat::colorize("&6Burned yourself for &a{$event->getTime()} &6seconds."));
        } else {
            $sender->sendMessage(TextFormat::colorize("&6Burned &c{$player->getName()}&r&6 for &c{$event->getTime()}&6 seconds."));
            $player->sendMessage(TextFormat::colorize("&6Burned yourself for &a{$event->getTime()} &6seconds."));
        }
    }
}