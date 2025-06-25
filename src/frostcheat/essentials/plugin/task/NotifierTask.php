<?php

namespace frostcheat\essentials\plugin\task;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Internet;
use function yaml_parse;
use function version_compare;
use function vsprintf;

class NotifierTask extends AsyncTask {

    public function __construct(
        private string $pluginName,
        private string $pluginVersion,
        private string $pluginAuthor
    ) {}

    public function onRun(): void {
        $url = "https://raw.githubusercontent.com/" . urlencode($this->pluginAuthor) . "/" . urlencode($this->pluginName) . "/main/plugin.yml";

        $response = Internet::getURL($url, 10, [], $err);

        $latestVersion = "";
        $api = "";
        $artifactUrl = "";

        if ($response !== null) {
            $rawYaml = $response->getBody();
            $data = @yaml_parse($rawYaml);

            if ($data && isset($data["version"])) {
                $latestVersion = $data["version"];
                $api = $data["api"] ?? "N/A";
                $artifactUrl = "https://github.com/" . urlencode($this->pluginAuthor) . "/" . urlencode($this->pluginName) . "/releases";
            } else {
                $err = "No se pudo leer la versión desde plugin.yml";
            }
        } else {
            $err = "No se pudo acceder al plugin.yml";
        }

        $this->setResult([$latestVersion, $artifactUrl, $api, $err]);
    }

    public function onCompletion(): void {
        $plugin = Server::getInstance()->getPluginManager()->getPlugin($this->pluginName);
        if ($plugin === null) return;

        [$latestVersion, $artifactUrl, $api, $err] = $this->getResult();

        if ($err !== null) {
            $plugin->getLogger()->error("Error al buscar actualización: $err");
            return;
        }

        if ($latestVersion !== "" && version_compare($this->pluginVersion, $latestVersion, '<')) {
            $plugin->getLogger()->notice(vsprintf(
                "Hay una nueva versión disponible: %s (API %s). Descárgala desde: %s",
                [$latestVersion, $api, $artifactUrl]
            ));
        }
    }
}
