<?php


namespace NotZ;

use NotZ\Arena\{Arena, ArenaCreator};
use NotZ\Events\CPSCounter;
use NotZ\Events\EventListener;
use NotZ\Execute\Commands;
use NotZ\Execute\HUB;
use pocketmine\data\bedrock\PotionTypeIdMap;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\PotionType;
use pocketmine\plugin\PluginBase;

class Core extends PluginBase
{
    private static Arena $arena;
    private static ArenaCreator $creator;
    private static CPSCounter $cps;
    private static self $instance;

    public static function getCreator(): ArenaCreator
    {
        return Core::$creator;
    }

    public static function getArena(): Arena
    {
        return Core::$arena;
    }

    public static function getPrefix(): string
    {
        return "§cBerry §f>> ";
    }

    public static function getCPSCounter(): CPSCounter
    {
        return self::$cps;
    }

    public function onLoad(): void
    {
        Core::$creator = new ArenaCreator();
        Core::$arena = new Arena();
        Core::$instance = $this;
        Core::$cps = new CPSCounter();
    }

    public function onEnable(): void
    {
        foreach (PotionType::getAll() as $type) {
            $typeId = PotionTypeIdMap::getInstance()->toId($type);
            ItemFactory::getInstance()->register(new CustomSplashPotion(new ItemIdentifier(ItemIds::SPLASH_POTION, $typeId), $type->getDisplayName() . " Splash Potion", $type), true);
        }
        $this->getScheduler()->scheduleRepeatingTask(new ScoreTagTask(), 3);
        $this->getServer()->getCommandMap()->register("core", new Commands());
        $this->getServer()->getCommandMap()->register("hub", new HUB());
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        @mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder() . "players/");
        @mkdir($this->getDataFolder() . "data/");
        $this->saveResource("/settings.yml");
    }

    public static function getInstance(): Core
    {
        return self::$instance;
    }
}