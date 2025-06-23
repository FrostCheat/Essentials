<?php

namespace frostcheat\essentials\commands\args;

use CortexPE\Commando\args\StringEnumArgument;

use pocketmine\command\CommandSender;
use pocketmine\player\GameMode;

final class GameModeArgument extends StringEnumArgument
{
    public function getTypeName() : string {
        return "gamemode";
    }

    public function canParse(string $testString, CommandSender $sender) : bool {
        return $this->getValue($testString) instanceof GameMode;
    }

    public function parse(string $argument, CommandSender $sender) : ?GameMode {
        return $this->getValue($argument);
    }

    public function getValue(string $string) : ?GameMode {
        return GameMode::fromString($string);
    }

    public function getEnumValues() : array {
        $gameModes = GameMode::getAll();

        $aliases = [];
        foreach ($gameModes as $mode) {
            foreach ($mode->getAliases() as $alias) {
                $aliases[] = strtolower($alias);
            }
        }

        return $aliases;
    }
}