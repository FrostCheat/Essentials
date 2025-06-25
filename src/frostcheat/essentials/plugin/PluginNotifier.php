<?php
declare(strict_types = 1);

namespace frostcheat\essentials\plugin;

use frostcheat\essentials\plugin\task\NotifierTask;
use pocketmine\Server;

class PluginNotifier {

	/**
	 * Submits an async task which then checks if a new version for the plugin is available.
	 * If an update is available then it would print a message on the console.
	 *
	 * @param string $pluginName
	 * @param string $pluginVersion
	 */
	public static function checkUpdate(string $pluginName, string $pluginVersion, array $pluginAuthor): void {
		Server::getInstance()->getAsyncPool()->submitTask(new NotifierTask($pluginName, $pluginVersion, $pluginAuthor[0] ?? $pluginName));
	}
}