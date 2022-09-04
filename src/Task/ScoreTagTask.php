<?php

namespace Kuu\Task;

use Kuu\Core;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class ScoreTagTask extends Task
{

    public function onRun(): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $ping = $player->getNetworkSession()->getPing();
            $cps = Core::getCPSCounter()->getClicks($player);
            $player->setScoreTag("§f$ping" . Core::COLOR . " MS§f | §f$cps" . Core::COLOR . " CPS");
        }
    }
}