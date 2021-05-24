<?php

namespace NotZ\Arena;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\TextFormat as Color;
use pocketmine\math\Vector3;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Effect;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\item\{Item, Potion};
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

use NotZ\Task\JoinTask;
use NotZ\utils\FormAPI\SimpleForm;
use NotZ\Core;

class Arena {
	
	private $arena;
	
	public function __construct(Core $arena){
		$this->arena = $arena;
		
	}
	
	public function getArena(){
		return $this->arena;
		
	}
	
	public function getMode(Player $player){
		if($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getGappleArena())){
		    return "Gapple";
		
		} else if($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getComboArena())){
		    return "Combo";
		
		} else if($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getDebuffArena())){
		    return "Debuff";
		
		} else if($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getFistArena())){
		    return "Fist";
		} else if($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getResistanceArena())){
		    return "Resistance";
		
		}
	}

	public function getPlayers(string $arena){
		if(!Server::getInstance()->getLevelByName($arena)){
			return Color::DARK_RED . "Unloaded World";
		} else {
			return count(Server::getInstance()->getLevelByName($arena)->getPlayers());
		}
	}
	
	public function playSound(Player $player, string $soundName, int $pitch = 1, int $volumen = 20){
		$pk = new PlaySoundPacket();
		$pk->soundName = $soundName;
		$pk->volume = $volumen;
		$pk->pitch = $pitch;
		$pk->x = $player->x;
		$pk->y = $player->y;
		$pk->z = $player->z;
		$player->dataPacket($pk);
		
		return;

	}
	
	public function onJoinGapple(Player $player){
		$world = Core::getCreator()->getGappleArena();
		$x = Core::getCreator()->getGappleSpawn();
		if($world != null){
			Server::getInstance()->loadLevel($world);
			$player->setGamemode(Player::ADVENTURE);
			$player->getInventory()->clearAll();
			$player->getArmorInventory()->clearAll();
			$player->removeAllEffects();
			$player->setAllowFlight(false);
            $player->setFlying(false);
            $player->setHealth(20);
            $player->setFood(20);
            $player->setScale(1);
            self::getKitGapple($player);
			$player->teleport(Server::getInstance()->getLevelByName($world)->getSafeSpawn());
			$player->teleport(new Vector3($x[0], $x[1]+0.6, $x[2]));
			self::playSound($player, 'jump.slime');

			
		} else {
			$player->sendMessage($this->getArena()->getPrefix() . Color::RED . "Arena not available");
		}
	}

	public function onJoinResistance(Player $player){
		$world = Core::getCreator()->getResistanceArena();
		$x = Core::getCreator()->getResistanceSpawn();
		if($world != null){
			Server::getInstance()->loadLevel($world);
			$player->setGamemode(Player::ADVENTURE);
			$player->getInventory()->clearAll();
			$player->getArmorInventory()->clearAll();
			$player->removeAllEffects();
			$player->setAllowFlight(false);
            $player->setFlying(false);
            $player->setHealth(20);
            $player->setFood(20);
            $player->setScale(1);
            self::getKitResistance($player);
			$player->teleport(Server::getInstance()->getLevelByName($world)->getSafeSpawn());
			$player->teleport(new Vector3($x[0], $x[1]+0.6, $x[2]));
			self::playSound($player, 'jump.slime');

			
		} else {
			$player->sendMessage($this->getArena()->getPrefix() . Color::RED . "Arena not available");
		}
	}
	
	public function onJoinCombo(Player $player){
		$world = Core::getCreator()->getComboArena();
		$x = Core::getCreator()->getComboSpawn();
		if($world != null){
			Server::getInstance()->loadLevel($world);
			$player->setGamemode(Player::ADVENTURE);
			$player->getInventory()->clearAll();
			$player->getArmorInventory()->clearAll();
			$player->removeAllEffects();
			$player->setAllowFlight(false);
            $player->setFlying(false);
            $player->setHealth(20);
            $player->setFood(20);
            $player->setScale(1);
            self::getKitCombo($player);
			$player->teleport(Server::getInstance()->getLevelByName($world)->getSafeSpawn());
			$player->teleport(new Vector3($x[0], $x[1]+0.6, $x[2]));
			self::playSound($player, 'jump.slime');
			
			
		} else {
			$player->sendMessage($this->getArena()->getPrefix() . Color::RED . "Arena not available");
		}
	}
	
	public function onJoinDebuff(Player $player){
		$world = Core::getCreator()->getDebuffArena();
		$x = Core::getCreator()->getDebuffSpawn();
		if($world != null){
			Server::getInstance()->loadLevel($world);
			$player->setGamemode(Player::ADVENTURE);
			$player->getInventory()->clearAll();
			$player->getArmorInventory()->clearAll();
			$player->removeAllEffects();
			$player->setAllowFlight(false);
            $player->setFlying(false);
            $player->setHealth(20);
            $player->setFood(20);
            $player->setScale(1);
            self::getKitDebuff($player);
			$player->teleport(Server::getInstance()->getLevelByName($world)->getSafeSpawn());
			$player->teleport(new Vector3($x[0], $x[1]+0.6, $x[2]));
			self::playSound($player, 'jump.slime');

			
		} else {
			$player->sendMessage($this->getArena()->getPrefix() . Color::RED . "Arena not available");
		}
	}
	
	public function onJoinFist(Player $player){
		$world = Core::getCreator()->getFistArena();
		$x = Core::getCreator()->getFistSpawn();
		if($world != null){
			Server::getInstance()->loadLevel($world);
			$player->setGamemode(Player::ADVENTURE);
			$player->getInventory()->clearAll();
			$player->getArmorInventory()->clearAll();
			$player->removeAllEffects();
			$player->setAllowFlight(false);
            $player->setFlying(false);
            $player->setMaxHealth(20);
            $player->setHealth(20);
            $player->setFood(20);
            $player->setScale(1);
            self::getKitFist($player);
			$player->teleport(Server::getInstance()->getLevelByName($world)->getSafeSpawn());
			$player->teleport(new Vector3($x[0], $x[1]+0.6, $x[2]));
			self::playSound($player, 'jump.slime');

		} else {
			$player->sendMessage($this->getArena()->getPrefix() . Color::RED . "Arena not available");
		}
	}
	
	public function getForm(Player $player){
		$form = new SimpleForm(function (Player $player, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
		
			switch ($result){
				case 0:
				self::onJoinGapple($player);
				break;
				case 1:
				self::onJoinCombo($player);
				break;
				case 2:
				self::onJoinDebuff($player);
				break;
				case 3:
				self::onJoinFist($player);
				break;
				case 4:
				self::onJoinResistance($player);
				break;
				case 5:
				$command = "kbffa join";
				Server::getInstance()->dispatchcommand($player, $command);
				break;
				case 6:
				$command = "nodebuff";
				Server::getInstance()->dispatchcommand($player, $command);
				break;
				default:
				return;
				
			}
			
		});

		$gapple = "§eCurrently Playing§0:§b " . self::getPlayers(Core::getCreator()->getGappleArena());
		$combo = "§eCurrently Playing§0:§b " . self::getPlayers(Core::getCreator()->getComboArena());
		$debuff = "§eCurrently Playing§0:§b " . self::getPlayers(Core::getCreator()->getDebuffArena());
		$fist = "§eCurrently Playing§0:§b " . self::getPlayers(Core::getCreator()->getFistArena());
		$resis = "§eCurrently Playing§0:§b " . self::getPlayers(Core::getCreator()->getResistanceArena());
		$bot = "§eCurrently Playing§0:§b " . self::getPlayers("bot");
		$kbffa = "§eCurrently Playing§0:§b " . self::getPlayers("kbffa");
		
		$form->setTitle("§cBerry §ePractice Core");
		$form->addButton("§6Gapple\n" . $gapple, 0, "textures/items/apple_golden.png");
		$form->addButton("§6Combo\n" . $combo, 0, "textures/items/feather.png");
		$form->addButton("§6Debuff\n" . $debuff, 0, "textures/items/potion_bottle_splash_saturation.png");
		$form->addButton("§6Fist\n" . $fist, 0, "textures/items/beef_cooked.png");
		$form->addButton("§6Resistance\n" . $resis, 0, "textures/items/snowball.png");
		$form->addButton("§6KnockBack\n" . $kbffa, 0, "textures/items/stick.png");
		$form->addButton("§6Bot\n". $bot, 0, "textures/items/apple.png");
		$form->sendToPlayer($player);
		return $form;
		
	}

	public function getKitGapple(Player $player){
		
        $inventory = $player->getInventory();
        $armorInventory = $player->getArmorInventory();

        $protection = Enchantment::getEnchantment(Enchantment::PROTECTION);
        $unbreaking = Enchantment::getEnchantment(Enchantment::UNBREAKING);
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance($protection));
        $helmet->addEnchantment(new EnchantmentInstance($unbreaking));
        $chestplate = Item::get(Item::DIAMOND_CHESTPLATE, 0, 1);
        $chestplate->addEnchantment(new EnchantmentInstance($protection));
        $chestplate->addEnchantment(new EnchantmentInstance($unbreaking));
        $leggings = Item::get(Item::DIAMOND_LEGGINGS, 0, 1);
        $leggings->addEnchantment(new EnchantmentInstance($protection));
        $leggings->addEnchantment(new EnchantmentInstance($unbreaking));
        $boots = Item::get(Item::DIAMOND_BOOTS, 0, 1);
        $boots->addEnchantment(new EnchantmentInstance($protection));
        $boots->addEnchantment(new EnchantmentInstance($unbreaking));
        $armorInventory->setHelmet($helmet);
        $armorInventory->setBoots($boots);
        $armorInventory->setChestplate($chestplate);
        $armorInventory->setLeggings($leggings);
        $armorInventory->sendContents($player);
        
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
	    $sword->addEnchantment((new EnchantmentInstance($unbreaking))->setLevel(2));
        $inventory->addItem($sword);
        $inventory->addItem(Item::get(Item::GOLDEN_APPLE, 0, 16));
        $inventory->sendContents($player);
	}
	
	public function getKitCombo(Player $player){
		
        $inventory = $player->getInventory();
        $armorInventory = $player->getArmorInventory();

        $protection = Enchantment::getEnchantment(Enchantment::PROTECTION);
        $unbreaking = Enchantment::getEnchantment(Enchantment::UNBREAKING);
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance($protection));
        $helmet->addEnchantment(new EnchantmentInstance($unbreaking));
        $chestplate = Item::get(Item::DIAMOND_CHESTPLATE, 0, 1);
        $chestplate->addEnchantment(new EnchantmentInstance($protection));
        $chestplate->addEnchantment(new EnchantmentInstance($unbreaking));
        $leggings = Item::get(Item::DIAMOND_LEGGINGS, 0, 1);
        $leggings->addEnchantment(new EnchantmentInstance($protection));
        $leggings->addEnchantment(new EnchantmentInstance($unbreaking));
        $boots = Item::get(Item::DIAMOND_BOOTS, 0, 1);
        $boots->addEnchantment(new EnchantmentInstance($protection));
        $boots->addEnchantment(new EnchantmentInstance($unbreaking));
        $armorInventory->setHelmet($helmet);
        $armorInventory->setBoots($boots);
        $armorInventory->setChestplate($chestplate);
        $armorInventory->setLeggings($leggings);
        $armorInventory->sendContents($player);
        
        $sharpness = Enchantment::getEnchantment(Enchantment::SHARPNESS);
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
	    $sword->addEnchantment((new EnchantmentInstance($sharpness))->setLevel(2));
        $inventory->addItem($sword);
        $inventory->addItem(Item::get(466, 0, 8));
        $inventory->sendContents($player);
	}
	
	public function getKitDebuff(Player $player){
		
        $inventory = $player->getInventory();
        $armorInventory = $player->getArmorInventory();

        $protection = Enchantment::getEnchantment(Enchantment::PROTECTION);
        $unbreaking = Enchantment::getEnchantment(Enchantment::UNBREAKING);
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance($protection));
        $helmet->addEnchantment(new EnchantmentInstance($unbreaking));
        $chestplate = Item::get(Item::DIAMOND_CHESTPLATE, 0, 1);
        $chestplate->addEnchantment(new EnchantmentInstance($protection));
        $chestplate->addEnchantment(new EnchantmentInstance($unbreaking));
        $leggings = Item::get(Item::DIAMOND_LEGGINGS, 0, 1);
        $leggings->addEnchantment(new EnchantmentInstance($protection));
        $leggings->addEnchantment(new EnchantmentInstance($unbreaking));
        $boots = Item::get(Item::DIAMOND_BOOTS, 0, 1);
        $boots->addEnchantment(new EnchantmentInstance($protection));
        $boots->addEnchantment(new EnchantmentInstance($unbreaking));
        $armorInventory->setHelmet($helmet);
        $armorInventory->setBoots($boots);
        $armorInventory->setChestplate($chestplate);
        $armorInventory->setLeggings($leggings);
        $armorInventory->sendContents($player);
        
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
	    $sword->addEnchantment((new EnchantmentInstance($unbreaking))->setLevel(2));
        $inventory->addItem($sword);
        $inventory->addItem(Item::get(368, 0, 16));
        $inventory->addItem(Item::get(Item::SPLASH_POTION, 22, 32));
        $inventory->addItem(Item::get(Item::SPLASH_POTION, Potion::SWIFTNESS, 10));
        $inventory->sendContents($player);
	}
	
	public function getKitFist(Player $player){
		
        $inventory = $player->getInventory();

        $inventory->addItem(Item::get(Item::STEAK, 0, 16));
        $inventory->sendContents($player);
	}

	public function getKitResistance(Player $player){
		
        $inventory = $player->getInventory();

        $inventory->addItem(Item::get(Item::STEAK, 0, 16));
        $inventory->sendContents($player);
		$eff = new EffectInstance(Effect::getEffect(Effect::RESISTANCE) , 4 * 999999, 255, true);
        $player->addEffect($eff);
	}
	
	public function getReKit(Player $player){
		if($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getGappleArena())){
			$player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 1));
		
		} else if($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getComboArena())){
		    $player->getInventory()->addItem(Item::get(466, 0, 1));
		
		} else if($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getDebuffArena())){
		    $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 32));
            $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, Potion::SWIFTNESS, 10));
		
		} else if($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getFistArena())){
		    $player->getInventory()->addItem(Item::get(Item::STEAK, 0, 1));
		
		} else if($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getResistanceArena())){
		    $player->getInventory()->addItem(Item::get(Item::STEAK, 0, 1));
		}
	}
	
}

?>
