<?php

namespace frostcheat\essentials\commands\fix;

use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\commands\fix\subcommands\AllSubCommand;
use frostcheat\essentials\commands\fix\subcommands\HandSubCommand;
use frostcheat\essentials\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class FixCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Loader::getInstance(), "fix", "Fix All/Hand items");
        $this->setPermission("essentials.command.fix");
    }

    public function prepare(): void {
        $this->registerSubCommand(new AllSubCommand());
        $this->registerSubCommand(new HandSubCommand());
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        foreach ($this->getSubCommands() as $subCommand) {
            $sender->sendMessage(TextFormat::colorize("&b/$aliasUsed " . $subCommand->getName() . " &f- &7" . $subCommand->getDescription()));
        }
    }
}