<?php

namespace frostcheat\essentials\commands;

use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\events\ServerBroadcastEvent;
use frostcheat\essentials\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class BroadcastCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Loader::getInstance(), "broadcast", "Broadcast a message");
        $this->setPermission("essentials.command.broadcast");
    }

    public function prepare(): void {
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (count($args) <= 0) {
            $sender->sendMessage(TextFormat::colorize("&cPlease enter a message."));
            return;
        }
        $str = implode(" ", $args);

        $event = new ServerBroadcastEvent($sender, $str);
        $event->call();

        if ($event->isCancelled()) return;

        Loader::getInstance()->getServer()->broadcastMessage(TextFormat::colorize($event->getMessage()));
    }
}