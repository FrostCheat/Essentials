<?php

namespace frostcheat\essentials\commands\essentials\subcommands;

use frostcheat\essentials\libs\CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class HelpSubCommand extends BaseSubCommand {

    public function __construct(private array $subCommands) {
        parent::__construct("help", "Shows this list");
        $this->setPermission("essentials.command.help");
    }

    public function prepare(): void {}

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        foreach ($this->subCommands as $subCommand) {
            $sender->sendMessage(TextFormat::colorize("&b/$aliasUsed " . $subCommand->getName() . " &f- &7" . $subCommand->getDescription()));
        }
    }
}