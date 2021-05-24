<?php


namespace NotZ;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\entity\Entity;
use pocketmine\utils\TextFormat as Color;

use NotZ\Execute\Commands;
use NotZ\Execute\HUB;
use NotZ\Events\EventListener;
use NotZ\utils\{Entities\JoinCore, Entities\CoreStatus};
use NotZ\Task\EntityTask;
use NotZ\Arena\{Arena, ArenaCreator};

class Core extends PluginBase implements Listener {
	
	private $data = [];
	
	private static $creator = null;
	
	public static $arena = null;

	public function onLoad(){
		
		
		$this->data["prefix"] = ("§cBerry §f>> ");
		
		Core::$creator = new ArenaCreator($this);
	
	    Core::$arena = new Arena($this);
		
	}
	
	public function onEnable(){
		
		$this->getServer()->getCommandMap()->register("core", new Commands($this));
		$this->getServer()->getCommandMap()->register("hub", new HUB($this));
		
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		
		new EntityTask($this);
		
		Entity::registerEntity(JoinCore::class, true);
		
		Entity::registerEntity(CoreStatus::class, true);
		
		@mkdir($this->getDataFolder());
		
		@mkdir($this->getDataFolder() . "players/");
		
		@mkdir($this->getDataFolder() . "data/");
		
		$this->saveResource("/settings.yml");
		
	}
	
	public function getPrefix() {
		return $this->data["prefix"];
	}
	
	public static function getCreator() : ArenaCreator {
		return Core::$creator;
	}
	
	public static function getArena() : Arena {
		return Core::$arena;
	}
	
}

?>
