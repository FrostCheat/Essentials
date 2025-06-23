<?php

namespace frostcheat\essentials\sessions;

use pocketmine\world\Position;

class Session {

    private string $name;
    private ?Position $lastPosition = null;
    private array $cooldowns = [];
    private ?string $nick = null;
    private bool $vanished = false;
    
    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getLastPosition(): ?Position {
        return $this->lastPosition;
    }

    public function setLastPosition(Position $lastPosition): void {
        $this->lastPosition = $lastPosition;
    }

    public function getCooldowns(): array {
        return $this->cooldowns;
    }

    public function addCooldown(string $name, int $cooldown): void {
        $this->cooldowns[$name] = $cooldown;
    }

    public function getCooldown(string $name): int {
        return $this->cooldowns[$name] ?? time();
    }

    public function setCooldowns(array $cooldowns): void {
        $this->cooldowns = $cooldowns;
    }

    public function getNick(): ?string {
        return $this->nick;
    }

    public function setNick(?string $nick): void {
        $this->nick = $nick;
    }

    public function isVanished(): bool {
        return $this->vanished;
    }

    public function setVanished(bool $vanished): void {
        $this->vanished = $vanished;
    }
}