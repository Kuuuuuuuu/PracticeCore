<?php

namespace Kuu;

use Kuu\Arena\{Arena, ArenaCreator};
use Kuu\Events\CPSCounter;
use Kuu\Events\EventListener;
use Kuu\Execute\CoreCommand;
use Kuu\Execute\HUB;
use pocketmine\data\bedrock\PotionTypeIdMap;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\PotionType;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

final class Core extends PluginBase
{
    public const COLOR = "§b";
    private const NAME = "§bPractice";
    private const PREFIX = "§f[§bPractice§f] ";

    private static Arena $arena;
    private static ArenaCreator $creator;
    private static CPSCounter $cps;
    private static self $instance;
    private static Utils $utils;

    public static function getCreator(): ArenaCreator
    {
        return self::$creator;
    }

    public static function getArena(): Arena
    {
        return self::$arena;
    }

    public static function getPrefix(): string
    {
        return self::PREFIX;
    }

    public static function getPluginName(): string
    {
        return self::NAME;
    }

    public static function getCPSCounter(): CPSCounter
    {
        return self::$cps;
    }

    public static function getCoreUtils(): Utils
    {
        return self::$utils;
    }

    public function onLoad(): void
    {
        self::$creator = new ArenaCreator();
        self::$arena = new Arena();
        self::$instance = $this;
        self::$cps = new CPSCounter();
        self::$utils = new Utils();
    }

    public function onEnable(): void
    {
        foreach (PotionType::getAll() as $type) {
            $typeId = PotionTypeIdMap::getInstance()->toId($type);
            ItemFactory::getInstance()->register(new CustomSplashPotion(new ItemIdentifier(ItemIds::SPLASH_POTION, $typeId), $type->getDisplayName() . " Splash Potion", $type), true);
        }
        $this->getLogger()->info("§aPlugin Enabled!");
        $this->getScheduler()->scheduleRepeatingTask(new ScoreTagTask(), 1);
        $this->getServer()->getCommandMap()->register("core", new CoreCommand());
        $this->getServer()->getCommandMap()->register("hub", new HUB());
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->loadallworlds();
        @mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder() . "players/");
        @mkdir($this->getDataFolder() . "data/");
    }

    public static function getInstance(): Core
    {
        return self::$instance;
    }

    public function loadallworlds(): void
    {
        foreach (glob(Server::getInstance()->getDataPath() . 'worlds/*') as $world) {
            $world = str_replace(Server::getInstance()->getDataPath() . 'worlds/', '', $world);
            if (Server::getInstance()->getWorldManager()->isWorldLoaded($world)) {
                continue;
            }
            Server::getInstance()->getWorldManager()->loadWorld($world, true);
        }
        foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world) {
            $world->setTime(0);
            $world->stopTime();
        }
    }

    public function onDisable(): void
    {
        $this->getLogger()->info("§cPlugin Disable!");
    }
}