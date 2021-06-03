<?php

namespace NotZ\Task;

use pocketmine\scheduler\Task;
use NotZ\Core;
use NotZ\Events\EventListener;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
class CPSTask extends Task{

    private $plugin;

    public function __construct(Core $plugin){
        $this->plugin = $plugin;
    }
   
    public function onRun(int $tick):void{
        foreach(Server::getInstance()->getOnlinePlayers() as $players){
        	if ($players->getLevel() !== Server::getInstance()->getLevelByName("world")) {
                 $cps = Server::getInstance()->getPluginManager()->getPlugin("CPS");
                 $popup = $cps->getClicks($players);
                 $players->sendTip("§f>> " . "§bCPS §f: §a" . $popup . " §f<<");
                }
            }
        }
    }
