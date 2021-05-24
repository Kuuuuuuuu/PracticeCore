<?php

namespace NotZ\Execute;

use pocketmine\Server;
use pocketmine\command\{PluginCommand, CommandSender};
use pocketmine\utils\TextFormat as Color;

use NotZ\utils\Entities\{CoreManager, JoinCore, CoreStatus};
use NotZ\utils\Permissions;
use NotZ\Core;

class Commands extends PluginCommand{
	
	private $command;
	
	public function __construct(Core $command){
		parent::__construct("core", $command);
		$this->setDescription("BerryCore info");
		$this->setUsage("use /core help");
		$this->command = $command;
	}
	
	public function getCommand(){
		return $this->command;
	}
	
	public function execute(CommandSender $sender, string $label, array $args): bool {
		if(!isset($args[0])){
			$sender->sendMessage(Color::BOLD . Color::WHITE . "» " . Color::RESET . Color::RED."use /core help");
			return false;
		}
		
		switch ($args[0]){
			case "help":
			if(!$sender->hasPermission(Permissions::CORE_ADMIN)){
				$sender->sendMessage(Color::BOLD."» " . Color::RESET.Color::RED."You don't have enough permissions to use this command");
				return false;
			}
			
			$sender->sendMessage(Color::BOLD.Color::YELLOW."=== FFA CORE ===");
			$sender->sendMessage(Color::YELLOW."/".$label.Color::BLUE." make <mode> <world>" . Color::GOLD . " - create new Arena for FFA");
			$sender->sendMessage(Color::YELLOW."/".$label.Color::BLUE." spawn <mode>" . Color::GOLD . " - set spawn Arena for FFA");
			$sender->sendMessage(Color::YELLOW."/".$label.Color::BLUE." remove <mode>" . Color::GOLD . " - delete Arena for FFA");
			$sender->sendMessage(Color::BOLD."» " . Color::RESET.Color::YELLOW."Modes: ".Color::GOLD."gapple, debuff, combo, fist");
			$sender->sendMessage(Color::YELLOW."/".$label.Color::BLUE." set-slapper" . Color::GOLD . " - set Slapper Join for FFA");
			$sender->sendMessage(Color::YELLOW."/".$label.Color::BLUE." set-tops" . Color::GOLD . " - set Tops for FFA");
			$sender->sendMessage(Color::YELLOW."/".$label.Color::BLUE." del-slapper" . Color::GOLD . " - killed Slappers");
			
			break;
			case "make":
			case "create":
			if(!$sender->hasPermission(Permissions::CORE_ADMIN)){
				$sender->sendMessage(Color::BOLD."» " . Color::RESET.Color::RED."You don't have enough permissions to use this command");
				return false;
			}
			
			if(!isset($args[1])){
				$sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."use /core make <mode> <world>");
				$sender->sendMessage(Color::YELLOW."Modes: ".Color::GOLD."gapple, debuff, combo, fist");
				return false;
			}
			
			if(!isset($args[2])){
				$sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."use /core make <mode> <world>");
				$sender->sendMessage(Color::YELLOW."Modes: ".Color::GOLD."gapple, debuff, combo, fist");
				return false;
			}
			switch ($args[1]){
				case "gapple":
				if(!file_exists(Server::getInstance()->getDataPath(). "worlds/" . $args[2])) {
					$sender->sendMessage($this->getCommand()->getPrefix() . Color::BOLD.Color::WHITE."» ".Color::RESET.Color::RED."World " .$args[2] . " not found");
				} else {
					 Server::getInstance()->loadLevel($args[2]);
					 $sender->teleport(Server::getInstance()->getLevelByName($args[2])->getSafeSpawn());
					 Core::getCreator()->setGappleArena($sender, $args[2]);
				}
				
				break;
				case "Resistance":
					if(!file_exists(Server::getInstance()->getDataPath(). "worlds/" . $args[2])) {
						$sender->sendMessage($this->getCommand()->getPrefix() . Color::BOLD.Color::WHITE."» ".Color::RESET.Color::RED."World " .$args[2] . " not found");
					} else {
						 Server::getInstance()->loadLevel($args[2]);
						 $sender->teleport(Server::getInstance()->getLevelByName($args[2])->getSafeSpawn());
						 Core::getCreator()->setResistanceArena($sender, $args[2]);
					}
					
					break;
				case "combo":
				if(!file_exists(Server::getInstance()->getDataPath(). "worlds/" . $args[2])) {
					$sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."World " .$args[2] . " not found");
				} else {
					 Server::getInstance()->loadLevel($args[2]);
					 $sender->teleport(Server::getInstance()->getLevelByName($args[2])->getSafeSpawn());
					 Core::getCreator()->setComboArena($sender, $args[2]);
				}
				
				break;
				case "debuff":
				if(!file_exists(Server::getInstance()->getDataPath(). "worlds/" . $args[2])) {
					$sender->sendMessage(Color::RED."World " .$args[2] . " not found");
				} else {
					 Server::getInstance()->loadLevel($args[2]);
					 $sender->teleport(Server::getInstance()->getLevelByName($args[2])->getSafeSpawn());
					 Core::getCreator()->setDebuffArena($sender, $args[2]);
				}
				break;
				case "fist":
				if(!file_exists(Server::getInstance()->getDataPath(). "worlds/" . $args[2])) {
					$sender->sendMessage(Color::RED."World " .$args[2] . " not found");
				} else {
					 Server::getInstance()->loadLevel($args[2]);
					 $sender->teleport(Server::getInstance()->getLevelByName($args[2])->getSafeSpawn());
					 Core::getCreator()->setFistArena($sender, $args[2]);
				}
				
				break;
				
				default:
				
				$sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."use /core make <mode> <world>");
				$sender->sendMessage(Color::YELLOW."Modes: ".Color::GOLD."gapple, debuff, combo, fist");
				
				break;
			}
			break;
			case "spawn":
			if(!$sender->hasPermission(Permissions::CORE_ADMIN)){
				$sender->sendMessage(Color::BOLD."» " . Color::RESET.Color::RED."You don't have enough permissions to use this command");
				return false;
			}
			
			if(!isset($args[1])){
				$sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."use /core spawn <mode>");
				$sender->sendMessage(Color::YELLOW."Modes: ".Color::GOLD."gapple, debuff, combo, fist");
				return false;
			}
			switch ($args[1]){
				case "gapple":
				$arena = Core::getCreator()->getGappleArena();
				if($arena != null){
					Core::getCreator()->setGappleSpawn($sender);
				} else {
				    $sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."The Gapple world has not registered");
				    $sender->sendMessage(Color::RED."use /core make <mode>");
				}
				
				break;
				case "Resistance":
					$arena = Core::getCreator()->getResistanceArena();
					if($arena != null){
						Core::getCreator()->setResistanceSpawn($sender);
					} else {
						$sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."The Resistance world has not registered");
						$sender->sendMessage(Color::RED."use /core make <mode>");
					}
					
					break;
				case "combo":
				$arena = Core::getCreator()->getComboArena();
				if($arena != null){
					Core::getCreator()->setComboSpawn($sender);
				} else {
				    $sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."The Combo world has not registered");
				    $sender->sendMessage(Color::RED."use /core make <mode>");
				}
				
				break;
				case "debuff":
				$arena = Core::getCreator()->getDebuffArena();
				if($arena != null){
					Core::getCreator()->setDebuffSpawn($sender);
				} else {
				    $sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."The Debuff world has not registered");
				    $sender->sendMessage(Color::RED."use /core make <mode>");
				}
				
				break;
				case "fist":
				$arena = Core::getCreator()->getFistArena();
				if($arena != null){
					Core::getCreator()->setFistSpawn($sender);
				} else {
				    $sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."The Fist world has not registered");
				    $sender->sendMessage(Color::RED."use /core make <mode>");
				}
				
				break;
				default:
				$sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."use /core spawn <mode>");
				$sender->sendMessage(Color::YELLOW."Modes: ".Color::GOLD."gapple, debuff, combo, fist");
				break;
			}
			break;
			case "remove":
			if(!$sender->hasPermission(Permissions::CORE_ADMIN)){
				$sender->sendMessage(Color::BOLD."» " . Color::RESET.Color::RED."You don't have enough permissions to use this command");
				return false;
			}
			if(!isset($args[1])){
				$sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."use /core remove <mode>");
				$sender->sendMessage(Color::YELLOW."Modes: ".Color::GOLD."gapple, debuff, combo, fist");
				return false;
			}
			switch ($args[1]){
				case "gapple":
				Core::getCreator()->removeGapple($sender);
				break;
				case "combo":
				Core::getCreator()->removeCombo($sender);
				break;
				case "debuff":
				Core::getCreator()->removeDebuff($sender);
				break;
				case "fist":
				Core::getCreator()->removeFist($sender);
				break;
				case "Resistance":
				Core::getCreator()->removeResistance($sender);
				break;
				default:
				$sender->sendMessage($this->getCommand()->getPrefix() . Color::RED."use /core remove <mode>");
				$sender->sendMessage(Color::YELLOW."Modes: ".Color::GOLD."gapple, debuff, combo, fist");
				break;
			}
			break;
			case "set-slapper":
			if(!$sender->hasPermission(Permissions::CORE_ADMIN)){
				$sender->sendMessage(Color::BOLD."» " . Color::RESET.Color::RED."You don't have enough permissions to use this command");
				return false;
			}
			
			$slapper = new CoreManager();
		    $slapper->setJoinEntity($sender->getPlayer());
		    $sender->sendMessage(Color::BOLD . Color::WHITE . "» " . Color::RESET . Color::GREEN . "Join Slapper Spawned");
			break;
			case "set-tops":
			if(!$sender->hasPermission(Permissions::CORE_ADMIN)){
				$sender->sendMessage(Color::BOLD."» " . Color::RESET.Color::RED."You don't have enough permissions to use this command");
				return false;
			}
			
			$slapper = new CoreManager();
		    $slapper->setStatus($sender->getPlayer());
		    $sender->sendMessage(Color::BOLD . Color::WHITE . "» " . Color::RESET . Color::GREEN . "Tops Spawned");
			break;
			case "del-slapper":
			if(!$sender->hasPermission(Permissions::CORE_ADMIN)){
				$sender->sendMessage(Color::BOLD."» " . Color::RESET.Color::RED."You don't have enough permissions to use this command");
				return false;
			}
			
			$npc = Server::getInstance()->getDefaultLevel()->getEntities();
				foreach ($npc as $entity){
					if($entity instanceof JoinCore){
						$sender->sendMessage(Color::BOLD . Color::WHITE . "» " . Color::RESET . Color::RED . "Entitie Join Killed");
						$entity->close();
					} else if($entity instanceof CoreStatus){
						$entity->close();
						$sender->sendMessage(Color::BOLD . Color::WHITE . "» " . Color::RESET . Color::RED . "Entitie Tops Killed");
					}
				}
			
			break;
			case "set-kb":
			if(!$sender->hasPermission(Permissions::CORE_ADMIN)){
				$sender->sendMessage(Color::BOLD."» " . Color::RESET.Color::RED."You don't have enough permissions to use this command");
				return false;
			}
			
			if(!isset($args[1])){
				$sender->sendMessage(Color::RED."use /core set-kb <value>");
				return false;
			}
			
			if(!is_numeric($args[1])){
				$sender->sendMessage(Color::BOLD . Color::WHITE . "» " . Color::RESET . Color::RED . "use a numeric value");
			    return false;
			}
			
			Core::getCreator()->setKB($sender, $args[1]);
			break;
			case "set-dl":
			if(!$sender->hasPermission(Permissions::CORE_ADMIN)){
				$sender->sendMessage(Color::BOLD."» " . Color::RESET.Color::RED."You don't have enough permissions to use this command");
				return false;
			}
			
			if(!isset($args[1])){
				$sender->sendMessage(Color::RED."use /core set-dl <value>");
				return false;
			}
			
			if(!is_numeric($args[1])){
				$sender->sendMessage(Color::BOLD . Color::WHITE . "» " . Color::RESET . Color::RED . "use a numeric value");
			    return false;
			}
			
			Core::getCreator()->setDL($sender, $args[1]);
			break;

			default:
			$sender->sendMessage(Color::GREEN."§cBerry§fCore §f>> §e plugin made by: ItsNotkungZ");
			break;
			
		}
		
		return true;
		
	}
	
}

?>
