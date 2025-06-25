<?php

namespace frostcheat\essentials\commands;

use frostcheat\essentials\libs\CortexPE\Commando\BaseCommand;
use frostcheat\essentials\Loader;
use frostcheat\essentials\sessions\SessionManager;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class NearCommand extends BaseCommand {
    public function __construct() {
        parent::__construct(Loader::getInstance(), "near", "Shows the players you have within a 100 block radius");
        $this->setPermission("essentials.command.near");
    }

    public function prepare(): void {}

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::colorize("&cYou must be a player to execute this command."));
            return;
        }

        $nearbyEntities = array_filter(
            $sender->getWorld()->getPlayers(),
            function (Player $target) use ($sender): bool {
                if ($target->getId() === $sender->getId()) {
                    return false;
                }

                $session = SessionManager::getInstance()->getSession($target->getName());
                if ($session !== null && $session->isVanished()) {
                    return false;
                }

                return $target->getPosition()->distance($sender->getPosition()) <= 100;
            }
        );

        if (count($nearbyEntities) === 0) {
            $sender->sendMessage(TextFormat::colorize('&cNo players.'));
            return;
        }

        $sender->sendMessage(TextFormat::colorize('&cNear players:' . PHP_EOL . implode(PHP_EOL, array_map(
            function (Player $player) use ($sender): string {
                $distance = (int) $sender->getPosition()->distance($player->getPosition());
                return "&f" . $player->getName() . " &7(" . $distance . "m)";
            },
            $nearbyEntities
        ))));
    }
}