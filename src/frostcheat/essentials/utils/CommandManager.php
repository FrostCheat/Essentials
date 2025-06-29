<?php

namespace frostcheat\essentials\utils;

use frostcheat\essentials\commands\BurnCommand;
use frostcheat\essentials\commands\BackCommand;
use frostcheat\essentials\commands\BroadcastCommand;
use frostcheat\essentials\commands\ClearCommand;
use frostcheat\essentials\commands\essentials\EssentialsCommand;
use frostcheat\essentials\commands\FeedCommand;
use frostcheat\essentials\commands\fix\FixCommand;
use frostcheat\essentials\commands\FlyCommand;
use frostcheat\essentials\commands\GameModeCommand;
use frostcheat\essentials\commands\GodCommand;
use frostcheat\essentials\commands\HealCommand;
use frostcheat\essentials\commands\MilkCommand;
use frostcheat\essentials\commands\NearCommand;
use frostcheat\essentials\commands\NickCommand;
use frostcheat\essentials\commands\PingCommand;
use frostcheat\essentials\commands\ReplyCommand;
use frostcheat\essentials\commands\TellCommand;
use frostcheat\essentials\commands\tpa\TpAcceptCommand;
use frostcheat\essentials\commands\tpa\TpaCommand;
use frostcheat\essentials\commands\tpa\TpaHereCommand;
use frostcheat\essentials\commands\tpa\TpDenyCommand;
use frostcheat\essentials\commands\TreeCommand;
use frostcheat\essentials\commands\VanishCommand;
use frostcheat\essentials\Loader;

use pocketmine\command\Command;
use pocketmine\utils\SingletonTrait;

class CommandManager {
    use SingletonTrait;

    public function getCommands(): array {
        return [
            new BackCommand(),
            new BroadcastCommand(),
            new BurnCommand(),
            new ClearCommand(),
            new EssentialsCommand(),
            new FeedCommand(),
            new FixCommand(),
            new FlyCommand(),
            new GameModeCommand(),
            new GodCommand(),
            new MilkCommand(),
            new HealCommand(),
            new NearCommand(),
            new NickCommand(),
            new PingCommand(),
            new ReplyCommand(),
            new TellCommand(),
            new TpAcceptCommand(),
            new TpaCommand(),
            new TpaHereCommand(),
            new TpDenyCommand(),
            new TreeCommand(),
            new VanishCommand()
        ];
    }

    public function registerCommands(): void {
        foreach ($this->getCommands() as $command) {
            if ($command instanceof Command) {
                Loader::getInstance()->getServer()->getCommandMap()->register("essentials", $command);
            }
        }
    }

    public function unRegisterCommands(array $commands): void {
        foreach ($commands as $command) {
            if (($c = Loader::getInstance()->getServer()->getCommandMap()->getCommand($command)) !== null) {
                Loader::getInstance()->getServer()->getCommandMap()->unregister($c);
            }
        }
    }
}