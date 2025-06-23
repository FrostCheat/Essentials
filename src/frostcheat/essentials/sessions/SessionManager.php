<?php

namespace frostcheat\essentials\sessions;

use pocketmine\utils\SingletonTrait;

class SessionManager {
    use SingletonTrait;

    private array $sessions = [];

    public function addSession(Session $session): void {
        $this->sessions[$session->getName()] = $session;
    }

    public function getSession(string $name): ?Session {
        return $this->sessions[$name] ?? null;
    }

    public function getAll(): array {
        return $this->sessions;
    }
}