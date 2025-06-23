<?php

namespace frostcheat\essentials\commands\essentials;

use CortexPE\Commando\BaseCommand;
use frostcheat\essentials\commands\essentials\subcommands\HelpSubCommand;
use frostcheat\essentials\commands\essentials\subcommands\ListSubCommand;
use frostcheat\essentials\commands\essentials\subcommands\ReloadSubCommand;
use frostcheat\essentials\commands\essentials\subcommands\SaveSubCommand;
use frostcheat\essentials\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class EssentialsCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Loader::getInstance(), "essentials", "Main Essentials Command", ["essential"]);
        $this->setPermission("essentials.command");
    }

    public function prepare(): void {
        $this->registerSubCommand(new ListSubCommand());
        $this->registerSubCommand(new ReloadSubCommand());
        $this->registerSubCommand(new SaveSubCommand());
        $this->registerSubCommand(new HelpSubCommand($this->getSubCommands()));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        $sender->sendMessage(TextFormat::colorize("&cUse: /$aliasUsed help"));
    }
}