<?php

namespace frostcheat\essentials\utils;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class Utils {

    public static function date(int|float $time) : string {
		$remaining = (int)$time;
		$s = $remaining % 60;

		$m = null;
		$h = null;
		$d = null;

		if ($remaining >= 60) {
			$m = floor(($remaining % 3600) / 60);

			if ($remaining >= 3600) {
				$h = floor(($remaining % 86400) / 3600);

				if ($remaining >= 3600 * 24) {
					$d = floor($remaining / 86400);
				}
			}
		}
		return ($m !== null ? ($h !== null ? ($d !== null ? "$d days " : "") . "$h hours " : "") . "$m minutes " : "") . "$s seconds";
	}

	public static function play(Player $p, string $soundName, float $volume = 1, float $pitch = 1):void {
		$pk = new PlaySoundPacket();
		$pk->soundName = $soundName;
		$pk->pitch = $pitch;
		$pk->volume =$volume;
		$pos = $p->getEyePos();
		$pk->x = $pos->x;
		$pk->y = $pos->y;
		$pk->z = $pos->z;
		$p->getNetworkSession()->sendDataPacket($pk);
	}
}