<?php

namespace NotZ\Arena;

use pocketmine\Player;
use pocketmine\utils\{Config, TextFormat as Color};

use NotZ\Core;

class ArenaCreator {
	
	private $creator;
	
	public function __construct(Core $creator){
		$this->creator = $creator;
		
	}
	
	public function getCreator(){
		return $this->creator;
		
	}
	
	public function getItemName(){
		$data = new Config ($this->getCreator()->getDataFolder()."/settings.yml", Config::YAML);
		return $data->get("item-name");
	}
	
	public function getGappleArena(){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		return $data->get("Gapple");
	}
	
	public function getGappleSpawn(){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		return $data->get("Gapple-Spawn");
	}
	
	public function getComboArena(){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		return $data->get("Combo");
	}
	
	public function getComboSpawn(){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		return $data->get("Combo-Spawn");
	}
	
	public function getDebuffArena(){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		return $data->get("Debuff");
	}
	
	public function getDebuffSpawn(){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		return $data->get("Debuff-Spawn");
	}
	
	public function getResistanceArena(){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		return $data->get("Resistance");
	}
	
	public function getResistanceSpawn(){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		return $data->get("Resistance-Spawn");
	}
	public function getFistArena(){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		return $data->get("Fist");
	}
	
	public function getFistSpawn(){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		return $data->get("Fist-Spawn");
	}
	
	public function setGappleArena(Player $player, string $world){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$data->set("Gapple", $world);
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GREEN . "Gapple Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GOLD . "use /core spawn gapple - to select the spawn");
		
	}
	
	public function setGappleSpawn(Player $player){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$x = $player->getX();
		$y = $player->getY();
		$z = $player->getZ();
		$xyz = array($x, $y, $z);
		$data->set("Gapple-Spawn", $xyz);
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GREEN . "Gapple spawn saved successfully");
	}
	
	public function removeGapple(Player $player){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$data->remove("Gapple");
		$data->remove("Gapple-Spawn");
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::RED . "Gapple removed arena");
	}
	
	public function setComboArena(Player $player, string $world){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$data->set("Combo", $world);
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GREEN . "Combo Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GOLD . "use /core spawn combo - to select the spawn");
		
	}
	
	public function setComboSpawn(Player $player){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$x = $player->getX();
		$y = $player->getY();
		$z = $player->getZ();
		$xyz = array($x, $y, $z);
		$data->set("Combo-Spawn", $xyz);
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GREEN . "Combo spawn saved successfully");
	}
	
	public function removeCombo(Player $player){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$data->remove("Combo");
		$data->remove("Combo-Spawn");
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::RED . "Combo removed arena");
	}
	
	public function setDebuffArena(Player $player, string $world){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$data->set("Debuff", $world);
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GREEN . "Debuff Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GOLD . "use /core spawn debuff - to select the spawn");
		
	}
	
	public function setDebuffSpawn(Player $player){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$x = $player->getX();
		$y = $player->getY();
		$z = $player->getZ();
		$xyz = array($x, $y, $z);
		$data->set("Debuff-Spawn", $xyz);
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GREEN . "Debuff spawn saved successfully");
	}
	
	public function removeDebuff(Player $player){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$data->remove("Debuff");
		$data->remove("Debuff-Spawn");
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::RED . "Debuff removed arena");
	}
	
	public function setFistArena(Player $player, string $world){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$data->set("Fist", $world);
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GREEN . "Fist Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GOLD . "use /core spawn fist - to select the spawn");
		
	}
	
	public function setFistSpawn(Player $player){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$x = $player->getX();
		$y = $player->getY();
		$z = $player->getZ();
		$xyz = array($x, $y, $z);
		$data->set("Fist-Spawn", $xyz);
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GREEN . "Fist spawn saved successfully");
	}
	
	public function removeFist(Player $player){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$data->remove("Fist");
		$data->remove("Fist-Spawn");
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::RED . "Fist removed arena");
	}
	
	public function setResistanceArena(Player $player, string $world){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$data->set("Resistance", $world);
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GREEN . "Resistance Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GOLD . "use /core spawn Resistance - to select the spawn");
		
	}
	
	public function setResistanceSpawn(Player $player){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$x = $player->getX();
		$y = $player->getY();
		$z = $player->getZ();
		$xyz = array($x, $y, $z);
		$data->set("Resistance-Spawn", $xyz);
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::GREEN . "Resistance spawn saved successfully");
	}
	
	public function removeResistance(Player $player){
		$data = new Config ($this->getCreator()->getDataFolder()."data/arenas.yml", Config::YAML);
		$data->remove("Resistance");
		$data->remove("Resistace-Spawn");
		$data->save();
		$player->sendMessage($this->getCreator()->getPrefix() . Color::RED . "Resistance removed arena");
    }
	
}

?>