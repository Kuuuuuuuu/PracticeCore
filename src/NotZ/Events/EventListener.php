<?php

namespace NotZ\Events;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Effect;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};
use pocketmine\item\Item;
use pocketmine\level\sound\GhastShootSound;
use pocketmine\item\EnderPearl;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\event\block\{BlockBreakEvent, BlockPlaceEvent};
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent, EntityLevelChangeEvent};
use pocketmine\event\player\{PlayerDeathEvent, PlayerInteractEvent, PlayerRespawnEvent, PlayerDropItemEvent, PlayerExhaustEvent, PlayerItemHeldEvent};
use pocketmine\utils\TextFormat as Color;

use NotZ\utils\Entities\{JoinCore, CoreStatus};
use NotZ\Core;
use NotZ\utils\FormAPI\SimpleForm;
use NotZ\Arena\Arena;
use pocketmine\utils\Config;

class EventListener implements Listener
{
    public $listener;
    private $pearlcd;
    
    public function __construct(Core $listener)
    {
        $this->listener = $listener;
    }
    
    public function getListener()
    {
        return $this->listener;
    }

    public function mForm($player)
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
  case 0:
  $command = "report";
  Server::getInstance()->dispatchcommand($player, $command);
  break;
  
  case 1:
  $command = "sprint";
  Server::getInstance()->dispatchcommand($player, $command);
  break;
  
  case 2:
  $command = "cape";
  Server::getInstance()->dispatchcommand($player, $command);
  break;
  
  case 3:
  $command = "nick";
  Server::getInstance()->dispatchcommand($player, $command);
  break;
  
            
            }
        });
        $form->setTitle("§cBerry §eSettings");
        $form->addButton("§6Reports §fPlayers", 0, "textures/items/iron_sword");
        $form->addButton("§6Auto §fSprint", 0, "textures/items/diamond_boots");
        $form->addButton("§6Change §fCape", 0, "textures/items/ender_pearl");
        $form->addButton("§6Change §fName", 0, "textures/items/snowball");
        $form->sendToPlayer($player);
        return true;
    }

    public function onInteract(PlayerInteractEvent $e){
		$player = $e->getPlayer();
		$item = $e->getItem();
		
		if ($item->getCustomName() == '§bSettings §f| §bClick to use' && $item->getId() == 347) {
		    $player->getLevel()->addSound(new GhastShootSound($player));
		    $this->mForm($player);
		}
		if ($item->getCustomName() == '§aPlay §f| §bClick to use' && $item->getId() == 345) {
		    $player->getLevel()->addSound(new GhastShootSound($player));
			Core::getArena()->getForm($player);
		}
	}

    public function PlayerJoin(PlayerJoinEvent $event)
    {
        Enchantment::registerEnchantment(new Enchantment(100, "", 0, 0, 0, 1));
        $enchantment = Enchantment::getEnchantment(100);
        $this->enchInst = new EnchantmentInstance($enchantment, 1);
        if ($event->getPlayer() instanceof Player) {
            $player = $event->getPlayer();
            $name = $player->getName();
            $player->setGamemode(Player::ADVENTURE);
            $item = Item::get(345);
            $item->setCustomName('§aPlay §f| §bClick to use');
            $item->addEnchantment($this->enchInst);
            $player->getInventory()->setItem(4, $item, true);
            $item2 = Item::get(347);
            $item2->setCustomName('§bSettings §f| §bClick to use');
            $item2->addEnchantment($this->enchInst);
            $player->getInventory()->setItem(8, $item2, true);
        }
    }

    public function PlayerQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $player->setGamemode(Player::ADVENTURE);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->kill();
    }

    public function Lightning(Player $player) :void
    {
        $light = new AddActorPacket();
        $light->type = "minecraft:lightning_bolt";
        $light->entityRuntimeId = Entity::$entityCount++;
        $light->metadata = [];
        $light->motion = null;
        $light->yaw = $player->getYaw();
        $light->pitch = $player->getPitch();
        $light->position = new Vector3($player->getX(), $player->getY(), $player->getZ());
        Server::getInstance()->broadcastPacket($player->getLevel()->getPlayers(), $light);
        $block = $player->getLevel()->getBlock($player->getPosition()->floor()->down());
        $particle = new DestroyBlockParticle(new Vector3($player->getX(), $player->getY(), $player->getZ()), $block);
        $player->getLevel()->addParticle($particle);
        $sound = new PlaySoundPacket();
        $sound->soundName = "ambient.weather.lightning.impact";
        $sound->x = $player->getX();
        $sound->y = $player->getY();
        $sound->z = $player->getZ();
        $sound->volume = 1;
        $sound->pitch = 1;
        Server::getInstance()->broadcastPacket($player->getLevel()->getPlayers(), $sound);
    }
    
    public function onBreak(BlockBreakEvent $ev)
    {
        $player = $ev->getPlayer();
        if ($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getGappleArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getComboArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getDebuffArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getFistArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getResistanceArena())) {
            $ev->setCancelled(true);
        }
    }
    
    public function onPlace(BlockPlaceEvent $ev)
    {
        $player = $ev->getPlayer();
        if ($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getGappleArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getComboArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getDebuffArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getFistArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getResistanceArena())) {
            $ev->setCancelled(true);
        }
    }
    
    public function onDrop(PlayerDropItemEvent $ev)
    {
        $player = $ev->getPlayer();
        if ($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getGappleArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getComboArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getDebuffArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getFistArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getResistanceArena()) || $player->getLevel() == Server::getInstance()->getLevelByName("world")) {
            $ev->setCancelled(true);
        }
    }
    
    public function onChange(EntityLevelChangeEvent $ev) {
        $player = $ev->getEntity();
        if ($player instanceof Player) {
            if ($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getGappleArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getComboArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getDebuffArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getFistArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getResistanceArena()) || $player->getLevel() == Server::getInstance()->getLevelByName("world")) {
                $player->setMaxHealth(20);
                $player->setHealth(20);
                $player->setGamemode(Player::ADVENTURE);
            }
        }
    }
    
    public function onEnderPearl(PlayerInteractEvent $event) {
        $item = $event->getItem();
        if($item instanceof EnderPearl) {
            $cooldown = 10;
            $player = $event->getPlayer();
            if (isset($this->pearlcd[$player->getName()]) and time() - $this->pearlcd[$player->getName()] < $cooldown) {
                $event->setCancelled(true);
                $time = time() - $this->pearlcd[$player->getName()];
                $message = ($this->getListener()->getPrefix() . "§bEnder Pearl Cooldown §e{cooldown}");
                $message = str_replace("{cooldown}", ($cooldown - $time), $message);
                $player->sendMessage($message);
            } else {
                $this->pearlcd[$player->getName()] = time();
            }
        }
    }

    public function onDamageFall(EntityDamageEvent $ev) {
        $player = $ev->getEntity();
        if ($player instanceof Player) {
            if ($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getGappleArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getComboArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getDebuffArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getFistArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getResistanceArena()) || $player->getLevel() == Server::getInstance()->getLevelByName("world")) {
                if ($ev->getCause() === EntityDamageEvent::CAUSE_FALL) {
                    $ev->setCancelled(true);
                }
            }
        }
    }

    public function onDeath(PlayerDeathEvent $ev)
    {
        $player = $ev->getPlayer();
        if ($player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getGappleArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getComboArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getDebuffArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getFistArena()) || $player->getLevel() == Server::getInstance()->getLevelByName(Core::getCreator()->getResistanceArena())) {
            $ev->setDrops([]);
            $ev->setDeathMessage("");
            $ev->getPlayer()->setGamemode(Player::ADVENTURE);
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $this->Lightning($ev->getPlayer());
            $cause = $ev->getEntity()->getLastDamageCause();
            if ($cause instanceof EntityDamageByEntityEvent) {
                $damager = $cause->getDamager();
                if ($damager instanceof Player) {
                    foreach ($damager->getLevel()->getPlayers() as $players) {
                        $players->sendMessage($this->getListener()->getPrefix() . Color::RED . $player->getName() . Color::GRAY . " was killed by " . Color::GREEN . $damager->getName());
                    }
                    Core::getArena()->getReKit($damager);
                    $damager->setHealth($damager->getMaxHealth());
                }
            }
        }
    }
    
    public function onRespawn(PlayerRespawnEvent $ev)
    {
        Enchantment::registerEnchantment(new Enchantment(100, "", 0, 0, 0, 1));
        $enchantment = Enchantment::getEnchantment(100);
        $this->enchInst = new EnchantmentInstance($enchantment, 1);
        $player = $ev->getPlayer();
        $player->teleport(Server::getInstance()->getDefaultLevel()->getSafeSpawn());
        $player->setGamemode(Player::ADVENTURE);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $item = Item::get(345);
        $item->setCustomName('§aPlay §f| §bClick to use');
        $item->addEnchantment($this->enchInst);
        $player->getInventory()->setItem(4, $item, true);
        $item2 = Item::get(347);
        $item2->setCustomName('§bSettings §f| §bClick to use');
        $item2->addEnchantment($this->enchInst);
        $player->getInventory()->setItem(8, $item2, true);
    }
	
	public function onFunction(EntityDamageByEntityEvent $ev){
		$npc = $ev->getEntity();
		$player = $ev->getDamager();
		if($npc instanceof JoinCore && $player instanceof Player){
			$ev->setCancelled(true);
			Core::getArena()->getForm($player);
		} else if($npc instanceof CoreStatus && $player instanceof Player){
			$ev->setCancelled(true);
		}
	}
}


?>
