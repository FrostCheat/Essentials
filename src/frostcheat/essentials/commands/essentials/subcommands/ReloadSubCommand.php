<?php

namespace frostcheat\essentials\commands\essentials\subcommands;

use frostcheat\essentials\libs\CortexPE\Commando\BaseSubCommand;
use frostcheat\essentials\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class ReloadSubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct("reload", "Reloads the plugin configuration");
        $this->setPermission("essentials.command.reload");
    }

    public function prepare(): void {}

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        Loader::getInstance()->reloadConfig();
        $sender->sendMessage(TextFormat::colorize("&aThe configuration has been successfully reloaded."));
    }
}