<?php

namespace frostcheat\essentials\commands;

use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\PlayerBackEvent;
use frostcheat\essentials\Loader;
use frostcheat\essentials\sessions\SessionManager;
use frostcheat\essentials\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class BackCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Loader::getInstance(), "back", "Teleport to your previous position");
        $this->setPermission("essentials.command.back");
    }

    public function prepare(): void {
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        $session = SessionManager::getInstance()->getSession($sender->getName());
        if (!$sender instanceof Player || $session === null) {
            $sender->sendMessage(TextFormat::colorize("&cYou must be a player to execute this command."));
            return;
        }

        if ($session->getCooldown("back") > time() && !$sender->hasPermission("essentials.command.back.bypass")) {
            $sender->sendMessage(TextFormat::colorize("&cYou must wait " . Utils::date($session->getCooldown("back") - time()) . "s to run this command again."));
            return;
        }

        if ($session->getLastPosition() === null) {
            $sender->sendMessage(TextFormat::colorize("&cYou do not have a last registered position."));
            return;
        }

        $event = new PlayerBackEvent($sender, $session->getLastPosition());
        $event->call();

        if ($event->isCancelled()) return;

        if (!$sender->hasPermission("essentials.command.back.bypass")) {
            $session->addCooldown("back", time() + (int) Loader::getInstance()->getConfig()->get("back.cooldown", 60));
        }

        $sender->teleport($event->getPosition());
        $sender->sendMessage(TextFormat::colorize("&6Teleporting to your previous position..."));
    }
}