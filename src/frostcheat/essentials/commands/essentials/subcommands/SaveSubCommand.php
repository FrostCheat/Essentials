<?php

namespace frostcheat\essentials\commands\essentials\subcommands;

use frostcheat\essentials\libs\CortexPE\Commando\BaseSubCommand;
use frostcheat\essentials\provider\Provider;
use frostcheat\essentials\sessions\SessionManager;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class SaveSubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct("save", "Save all sessions");
        $this->setPermission("essentials.command.save");
    }

    public function prepare(): void {}

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        Provider::getInstance()->saveAllSessions();
        $sender->sendMessage(TextFormat::colorize("&aAll sessions (" . count(SessionManager::getInstance()->getAll()) . ") have been saved perfectly."));
    }
}