<?php

namespace frostcheat\essentials\events;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerPrivateMessageEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

	/** @var string */
	private string $message;
	/** @var string */
	private Player $recipient;

	public function __construct(Player $sender, string $message, Player $recipient) {
		$this->player = $sender;
		$this->message = $message;
		$this->recipient = $recipient;
	}

	/**
	 * @return string
	 */
	public function getMessage(): string {
		return $this->message;
	}

    public function setMessage(string $message): void {
        $this->message = $message;
    }

	/**
	 * @return Player
	 */
	public function getRecipient(): Player {
		return $this->recipient;
	}

    public function setRecipient(Player $recipient): void {
        $this->recipient = $recipient;
    }
}