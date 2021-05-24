<?php

namespace NotZ\Task;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\utils\{Config, TextFormat as Color};
use pocketmine\entity\{Effect, EffectInstance};

use NotZ\utils\Entities\{JoinCore, CoreStatus};
use NotZ\Core;

class EntityTask extends Task {
	
	private $entity;
	
	public function __construct(Core $entity){
        $this->entity = $entity;
        $this->entity->getScheduler()->scheduleRepeatingTask($this, 60);
    }
	
	public function onRun(int $currentTick){
		$level = $this->entity->getServer()->getDefaultLevel();
		$cfg = new Config ($this->entity->getDataFolder()."/settings.yml", Config::YAML);
		foreach ($level->getEntities() as $entity) {
			if ($entity instanceof JoinCore) {
				$entity->setNameTag($this->setName());
				$entity->setNameTagAlwaysVisible(true);
				$entity->setImmobile(true);
				$entity->setScale($cfg->get("slapper-size"));
				$entity->addEffect((new EffectInstance(Effect::getEffect(Effect::REGENERATION)))->setDuration(999990)->setAmplifier(6)->setVisible(false));
				
			} else if ($entity instanceof CoreStatus) {
				$entity->setNameTag($this->setStatus());
				$entity->setNameTagAlwaysVisible(true);
				
			}
		}
	}
	
	private function setName(): string {
		$colors = [Color::AQUA, Color::GOLD, Color::LIGHT_PURPLE, Color::GREEN];
		$title = $colors[mt_rand(0,3)] . "FreeForAll [Modes] " . "\n" . Color::GRAY . "Click to Select";
		return $title;
	}
	
	public function setStatus(): string {
		$data = new Config ($this->entity->getDataFolder()."players/kills.yml", Config::YAML);
		$tops = $data->getAll();
        arsort($tops);
        $tops = array_slice($tops, 0, 9);
        $counter = 0;
        $text = Color::YELLOW . "§cBerry §a Kills §fLeaderboards\n" . Color::GRAY . "Top 10 players hit most Kills";
        foreach ($tops as $key => $value) {
            $counter++;
            $text.= "\n" . Color::WHITE . "» " . Color::GREEN . $counter . Color::GOLD . " Player: " . Color::BLUE . $key . Color::GOLD . " Kills: " . Color::YELLOW . $value;
        }
		return $text;
	}
	
}