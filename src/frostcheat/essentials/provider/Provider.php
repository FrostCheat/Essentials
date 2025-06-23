<?php

namespace frostcheat\essentials\provider;

use frostcheat\essentials\Loader;
use frostcheat\essentials\sessions\Session;
use frostcheat\essentials\sessions\SessionManager;
use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\Position;

class Provider {
    use SingletonTrait;

    private string $dataFolder;

    public function init(string $dataFolder): void {
        $this->dataFolder = $dataFolder . "sessions/";
        @mkdir($this->dataFolder);

        $this->loadAllSessions();
    }

    public function loadAllSessions(): void {
        foreach (glob($this->dataFolder . "*.json") as $file) {
            $data = json_decode(file_get_contents($file), true);
            $name = basename($file, ".json");

            $session = new Session($name);

            if (isset($data["lastPosition"])) {
                $pos = $data["lastPosition"];
                $world = Loader::getInstance()->getServer()->getWorldManager()->getWorldByName($pos["world"] ?? "");
                if ($world !== null) {
                    $session->setLastPosition(new Position($pos["x"], $pos["y"], $pos["z"], $world));
                }
            }

            if (isset($data["cooldowns"])) {
                $session->setCooldowns($data["cooldowns"]);
            }

            if (isset($data["nick"])) {
                $session->setNick($data["nick"]);
            }

            SessionManager::getInstance()->addSession($session);
        }
    }

    public function saveSession(Session $session): void {
        $pos = $session->getLastPosition();
        $data = [
            "lastPosition" => $pos !== null ? [
                "x" => $pos->getX(),
                "y" => $pos->getY(),
                "z" => $pos->getZ(),
                "world" => $pos->getWorld()->getFolderName()
            ] : null,
            "cooldowns" => $session->getCooldowns(),
            "nick" => $session->getNick(),
        ];

        $file = $this->dataFolder . $session->getName() . ".json";

        Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new class($file, json_encode($data, JSON_PRETTY_PRINT)) extends AsyncTask {
            public function __construct(
                private string $file,
                private string $json
            ) {}

            public function onRun(): void {
                file_put_contents($this->file, $this->json);
            }
        });
    }


    public function saveAllSessions(): void {
        foreach (SessionManager::getInstance()->getAll() as $session) {
            $this->saveSession($session);
        }
    }
}
