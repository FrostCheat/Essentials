<?php

namespace frostcheat\essentials;

use frostcheat\essentials\provider\Provider;

use CortexPE\Commando\PacketHooker;

use frostcheat\essentials\utils\CommandManager;
use JackMD\ConfigUpdater\ConfigUpdater;
use JackMD\UpdateNotifier\UpdateNotifier;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\SingletonTrait;

class Loader extends PluginBase {
    use SingletonTrait;

    private const CONFIG_VERSION = 1;

    public function onLoad(): void {
        self::setInstance($this);
        Provider::getInstance()->init($this->getDataFolder());
    }

    public function onEnable(): void {
        UpdateNotifier::checkUpdate($this->getDescription()->getName(), $this->getDescription()->getVersion());
        if (ConfigUpdater::checkUpdate($this, $this->getConfig(), "config-version", self::CONFIG_VERSION)) {
            $this->reloadConfig();
        }

        if (!PacketHooker::isRegistered())
            PacketHooker::register($this);

        $this->saveDefaultConfig();

        CommandManager::getInstance()->unRegisterCommands(["clear"]);
        CommandManager::getInstance()->registerCommands();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function(): void {
            Provider::getInstance()->saveAllSessions();
        }), 20 * (int) $this->getConfig()->get("saveTime", 600));

    }

    public function onDisable(): void {
        Provider::getInstance()->saveAllSessions();
    }
}