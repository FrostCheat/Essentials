<?php

namespace frostcheat\essentials\commands\essentials\subcommands;

use frostcheat\essentials\libs\CortexPE\Commando\BaseSubCommand;
use frostcheat\essentials\utils\CommandManager;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class ListSubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct("list", "Displays the list of all plugin commands");
        $this->setPermission("essentials.command.list");
    }

    public function prepare(): void {}

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        foreach (CommandManager::getInstance()->getCommands() as $command) {
            $sender->sendMessage(TextFormat::colorize("&b/{$command->getName()} &f- &7{$command->getDescription()}"));
        }
    }
}