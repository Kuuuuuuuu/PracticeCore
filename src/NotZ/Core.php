<?php


namespace NotZ;

use NotZ\Arena\{Arena, ArenaCreator};
use NotZ\Events\EventListener;
use NotZ\Execute\Commands;
use NotZ\Execute\HUB;
use pocketmine\plugin\PluginBase;

class Core extends PluginBase
{
    private static Arena $arena;
    private static ArenaCreator $creator;
    private static self $instance;

    public static function getCreator(): ArenaCreator
    {
        return Core::$creator;
    }

    public static function getArena(): Arena
    {
        return Core::$arena;
    }

    public static function getInstance(): Core
    {
        return self::$instance;
    }

    public function onLoad(): void
    {
        Core::$creator = new ArenaCreator();
        Core::$arena = new Arena();
        Core::$instance = $this;
    }

    public function onEnable(): void
    {

        $this->getServer()->getCommandMap()->register("core", new Commands());
        $this->getServer()->getCommandMap()->register("hub", new HUB());
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        @mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder() . "players/");
        @mkdir($this->getDataFolder() . "data/");
        $this->saveResource("/settings.yml");
    }

    public static function getPrefix(): string
    {
        return "§cBerry §f>> ";
    }
}