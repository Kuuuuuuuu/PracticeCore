<?php

namespace NotZ\utils\FormAPI;

use pocketmine\form\Form as IForm;
use pocketmine\player\Player;
use ReturnTypeWillChange;

abstract class Form implements IForm
{

    protected array $data = [];

    private $callable;

    public function __construct(?callable $callable)
    {
        $this->callable = $callable;
    }

    public function sendToPlayer(Player $player): void
    {
        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        $this->processData($data);
        $callable = $this->getCallable();
        if ($callable !== null) {
            $callable($player, $data);
        }
    }

    public function processData(&$data): void
    {
    }

    public function getCallable(): ?callable
    {
        return $this->callable;
    }

    public function setCallable(?callable $callable)
    {
        $this->callable = $callable;
    }

    #[ReturnTypeWillChange] public function jsonSerialize()
    {
        return $this->data;
    }
}
