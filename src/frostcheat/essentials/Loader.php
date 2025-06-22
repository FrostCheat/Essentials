<?php

namespace frostcheat\essentials;

use frostcheat\essentials\commands\BackCommand;
use frostcheat\essentials\commands\FlyCommand;
use frostcheat\essentials\commands\PingCommand;

use CortexPE\Commando\PacketHooker;

use JackMD\ConfigUpdater\ConfigUpdater;
use JackMD\UpdateNotifier\UpdateNotifier;

use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Loader extends PluginBase {
    use SingletonTrait;

    private const CONFIG_VERSION = 1;

    public function onLoad(): void {
        self::setInstance($this);
    }

    public function onEnable(): void {
        UpdateNotifier::checkUpdate($this->getDescription()->getName(), $this->getDescription()->getVersion());
        if (ConfigUpdater::checkUpdate($this, $this->getConfig(), "config-version", self::CONFIG_VERSION)) {
            $this->reloadConfig();
        }

        if (!PacketHooker::isRegistered())
            PacketHooker::register($this);

        $this->saveDefaultConfig();

        $this->registerCommands([
            new BackCommand(),
            new FlyCommand(),
            new PingCommand(),
        ]);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    public function registerCommands(array $commands): void {
        foreach ($commands as $command) {
            if ($command instanceof Command) {
                $this->getServer()->getCommandMap()->register("essentials", $command);
            }
        }
    }
}