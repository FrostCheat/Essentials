<?php

namespace frostcheat\essentials\commands\tpa;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\TeleportRespondEvent;
use frostcheat\essentials\Loader;
use frostcheat\essentials\sessions\Session;
use frostcheat\essentials\sessions\SessionManager;
use frostcheat\essentials\sessions\TeleportRequest;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class TpAcceptCommand extends BaseCommand {
    public function __construct() {
        parent::__construct(Loader::getInstance(), "tpaccept", "Accept a player's teleport request");
        $this->setPermission("essentials.command.tpaccept");
    }

    public function prepare(): void {
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::colorize("&cYou must be a player to execute this command."));
            return;
        }

        $session = SessionManager::getInstance()->getSession($sender->getName());
        if ($session === null) {
            $sender->sendMessage(TextFormat::colorize("&cError loading session..."));
            return;
        }

        if (isset($args["player"])) {
            $target = Loader::getInstance()->getServer()->getPlayerExact($args["player"]);
            if (!$target instanceof Player) {
                $sender->sendMessage(TextFormat::colorize("&cPlayer not found."));
                return;
            }

            $request = $session->getTPRequest($target->getName());
            if ($request === null) {
                $sender->sendMessage(TextFormat::colorize("&cYou have no pending requests."));
                return;
            }

            $this->accept($request, $sender, $session);
            return;
        }

        $request = $session->getFirstValidRequest();
        if ($request === null) {
            $sender->sendMessage(TextFormat::colorize("&cYou have no pending requests."));
            return;
        }

        $this->accept($request, $sender, $session);
    }

    public function accept(TeleportRequest $request, Player $sender, Session $session): void {
        $event = new TeleportRespondEvent($sender, true);
        $event->call();

        if ($event->isCancelled()) return;

        $request->teleport();
        $request->getSender()->sendMessage(TextFormat::colorize("&6Your teleport request has been accepted."));
        $session->removeTPRequest($request);
    }
}