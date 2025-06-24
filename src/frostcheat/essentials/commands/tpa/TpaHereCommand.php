<?php

namespace frostcheat\essentials\commands\tpa;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\TeleportRequestEvent;
use frostcheat\essentials\Loader;
use frostcheat\essentials\sessions\SessionManager;
use frostcheat\essentials\sessions\TeleportRequest;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class TpaHereCommand extends BaseCommand {
    public function __construct() {
        parent::__construct(Loader::getInstance(), "tpahere", "Request a player to teleport to you");
        $this->setPermission("essentials.comman.tpahere");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("player"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::colorize("&cYou must be a player to execute this command."));
            return;
        }

        $target = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
        $session = SessionManager::getInstance()->getSession($args["player"]);
        if (!$target instanceof Player || $session === null) {
            $sender->sendMessage(TextFormat::colorize("&cPlayer not found."));
            return;
        }

        if ($target->getName() === $sender->getName()) {
            $sender->sendMessage(TextFormat::colorize("&cTarget can't be yourself."));
            return;
        }

        $request = new TeleportRequest($target, $sender, time() + (int) Loader::getInstance()->getConfig()->get("tpa.timeout", 120), true);
        $event = new TeleportRequestEvent($target, $sender, $request);
        $event->call();

        if ($event->isCancelled()) return;

        $session->addTPRequest($event->getRequest());
        $sender->sendMessage(TextFormat::colorize("&6Sent a teleport request to &c{$event->getPlayer()->getName()}&6."));
    }
}