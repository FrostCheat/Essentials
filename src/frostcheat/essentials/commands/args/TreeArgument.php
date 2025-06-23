<?php

namespace frostcheat\essentials\commands\args;

use CortexPE\Commando\args\StringEnumArgument;

use pocketmine\command\CommandSender;
use pocketmine\world\generator\object\TreeType;

final class TreeArgument extends StringEnumArgument
{
    public function getTypeName() : string {
        return "tree";
    }

    public function canParse(string $testString, CommandSender $sender) : bool {
        return $this->getValue($testString) instanceof TreeType;
    }

    public function parse(string $argument, CommandSender $sender) : ?TreeType {
        return $this->getValue($argument);
    }

    public function getValue(string $string) : ?TreeType {
        return $this->getTypes()[$string] ?? null;
    }

    public function getEnumValues() : array {
        return array_keys($this->getTypes());
    }

    /**
     * @return array<string, TreeType>
     */
    private function getTypes() : array {
        return [
            "oak" => TreeType::OAK(),
            "spruce" => TreeType::SPRUCE(),
            "birch" => TreeType::BIRCH(),
            "jungle" => TreeType::JUNGLE(),
            "acacia" => TreeType::ACACIA(),
        ];
    }
}
