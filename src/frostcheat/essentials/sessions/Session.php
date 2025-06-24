<?php

namespace frostcheat\essentials\sessions;

use frostcheat\essentials\Loader;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;

class Session {

    private string $name;
    private ?Position $lastPosition = null;
    private array $cooldowns = [];
    private ?string $nick = null;
    private bool $vanished = false;
    private ?string $lastPlayer = null;
    private array $tpRequests = [];
    private bool $godMode = false;
    
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

    public function getPlayer(): ?Player {
        return Loader::getInstance()->getServer()->getPlayerExact($this->getName());
    }

    public function setVanished(bool $vanished): void {
        $this->vanished = $vanished;
        $player = $this->getPlayer();

        if ($player === null) return;

        $server = Loader::getInstance()->getServer();

        foreach ($server->getOnlinePlayers() as $target) {
            if ($player->getId() === $target->getId()) {
                continue;
            }

            if ($vanished) {
                if (!$target->hasPermission("essentials.vanish.see")) {
                    $target->hidePlayer($player);
                }
            } else {
                if (!$target->canSee($player)) {
                    $target->showPlayer($player);
                }
            }
        }
    }

    public function getLastPlayer(): ?string {
        return $this->lastPlayer;
    }

    public function setLastPlayer(string $player): void {
        $this->lastPlayer = $player;
    }

    public function getTPRequests(): array {
        return $this->tpRequests;
    }

    public function addTPRequest(TeleportRequest $tpRequest): void {
        $this->tpRequests[$tpRequest->getTarget()->getName()] = $tpRequest;

        $player = $this->getPlayer();
        if ($player === null) return;

        $message = "&c" . $tpRequest->getTarget()->getName(). " &6has requested " .
            ($tpRequest->isHere() ? "you to teleport to them" : "to teleport to you") . ".\n" .
            "&6To teleport, type &c/tpaccept&6.\n&6To deny this request, type &c/tpdeny&6.\n" .
            "&6This request will timeout after &c" .
            Loader::getInstance()->getConfig()->get("tpa.timeout", 120) . " seconds&6.";

        $player->sendMessage(TextFormat::colorize($message));
    }


    public function getTPRequest(string $name): ?TeleportRequest {
        return isset($this->tpRequests[$name]) && !$this->tpRequests[$name]->isExpired()
            ? $this->tpRequests[$name]
            : null;
    }


    public function getFirstValidRequest(): ?TeleportRequest {
        foreach ($this->tpRequests as $tpRequest) {
            if (!$tpRequest->isExpired()) {
                return $tpRequest;
            }
        }
        return null;
    }

    public function removeTPRequest(TeleportRequest $tpRequest): void {
        unset($this->tpRequests[$tpRequest->getTarget()->getName()]);
    }

    public function isGodMode(): bool {
        return $this->godMode;
    }

    public function setGodMode(bool $godMode): void {
        $this->godMode = $godMode;
    }
}