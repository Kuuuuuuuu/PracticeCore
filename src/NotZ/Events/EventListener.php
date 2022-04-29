<?php

namespace NotZ\Events;

use NotZ\Core;
use NotZ\utils\FormAPI\CustomForm;
use NotZ\utils\FormAPI\SimpleForm;
use pocketmine\entity\projectile\SplashPotion;
use pocketmine\event\block\{BlockBreakEvent, BlockPlaceEvent};
use pocketmine\event\entity\{EntityDamageByEntityEvent, EntityDamageEvent, ProjectileHitBlockEvent};
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerDeathEvent, PlayerDropItemEvent, PlayerItemUseEvent, PlayerRespawnEvent};
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\enchantment\{EnchantmentInstance, VanillaEnchantments};
use pocketmine\item\EnderPearl;
use pocketmine\item\PotionType;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as Color;

class EventListener implements Listener
{
    private array $pearlcd = [];

    public function onUseItem(PlayerItemUseEvent $e)
    {
        $player = $e->getPlayer();
        $item = $e->getItem();
        if ($item instanceof EnderPearl) {
            $cooldown = 10;
            $player = $e->getPlayer();
            if (isset($this->pearlcd[$player->getName()]) and time() - $this->pearlcd[$player->getName()] < $cooldown) {
                $e->cancel();
                $time = time() - $this->pearlcd[$player->getName()];
                $message = (Core::getPrefix() . "§bEnder Pearl Cooldown §e{cooldown}");
                $message = str_replace("{cooldown}", ($cooldown - $time), $message);
                $player->sendMessage($message);
            } else {
                $this->pearlcd[$player->getName()] = time();
            }
        }
        if ($item->getCustomName() === '§bSettings §f| §bClick to use') {
            $this->mForm($player);
        } else if ($item->getCustomName() === '§aPlay §f| §bClick to use') {
            Core::getArena()->getForm($player);
        }
    }

    public function mForm($player): bool
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                // here just find the plugin for this
                case 0:
                    $command = "report";
                    Server::getInstance()->dispatchcommand($player, $command);
                    break;
                case 1:
                    $command = "cape";
                    Server::getInstance()->dispatchcommand($player, $command);
                    break;
                case 2:
                    $form = new CustomForm(function (Player $player, array $data = null) {
                        $result = $data;
                        if ($result === null) {
                            return true;
                        }
                        $name = $result[0];
                        $player->setDisplayName($name);
                        $player->setNameTag($name);
                        $player->sendMessage(Core::getPrefix() . "Your name has been changed to " . $name);
                        return true;
                    });
                    $form->setTitle(Color::GREEN . "Change your name");
                    $form->addInput(Color::GREEN . "Your name");
                    $form->sendToPlayer($player);
                    break;
            }
            return true;
        });
        $form->setTitle("§cBerry §eSettings");
        $form->addButton("§6Reports §fPlayers", 0, "textures/items/iron_sword");
        $form->addButton("§6Change §fCape", 0, "textures/items/ender_pearl");
        $form->addButton("§6Change §fName", 0, "textures/items/snowball");
        $form->sendToPlayer($player);
        return true;
    }

    public function onHit(ProjectileHitBlockEvent $event)
    {
        $projectile = $event->getEntity();
        if ($projectile instanceof SplashPotion and $projectile->getPotionType() === PotionType::STRONG_HEALING()) {
            $player = $projectile->getOwningEntity();
            if ($player instanceof Player and $player->isAlive() and $projectile->getPosition()->distance($player->getPosition()) <= 3) {
                $player->setHealth($player->getHealth() + 3.5);
            }
        }
    }

    public function PlayerJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $player->setGamemode(GameMode::ADVENTURE());
        Core::getCPSCounter()->initPlayerClickData($player);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $item = VanillaItems::IRON_SWORD()->setCustomName('§aPlay §f| §bClick to use')->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 10));
        $item2 = VanillaItems::BOW()->setCustomName('§bSettings §f| §bClick to use')->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 10));
        $player->getInventory()->setItem(8, $item2);
        $player->getInventory()->setItem(4, $item);
    }

    public function PlayerQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $player->setGamemode(GameMode::ADVENTURE());
        $player->getInventory()->clearAll();
        Core::getCPSCounter()->removePlayerClickData($player);
        $player->getArmorInventory()->clearAll();
        $player->kill();
    }

    public function onBreak(BlockBreakEvent $ev)
    {
        $player = $ev->getPlayer();
        if ($player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getGappleArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getComboArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getNodebuffArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getFistArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getResistanceArena())) {
            $ev->cancel();
        }
    }

    public function onPlace(BlockPlaceEvent $ev)
    {
        $player = $ev->getPlayer();
        if ($player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getGappleArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getComboArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getNodebuffArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getFistArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getResistanceArena())) {
            $ev->cancel();
        }
    }

    public function onDrop(PlayerDropItemEvent $ev)
    {
        $player = $ev->getPlayer();
        if ($player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getGappleArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getComboArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getNodebuffArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getFistArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getResistanceArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getDefaultWorld()) {
            $ev->cancel();
        }
    }

    public function onDamageFall(EntityDamageEvent $ev)
    {
        $player = $ev->getEntity();
        if ($player instanceof Player) {
            if ($player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getGappleArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getComboArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getNodebuffArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getFistArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getResistanceArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getDefaultWorld()) {
                if ($ev->getCause() === EntityDamageEvent::CAUSE_FALL) {
                    $ev->cancel();
                }
            }
        }
    }

    public function onDeath(PlayerDeathEvent $ev)
    {
        $player = $ev->getPlayer();
        if ($player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getGappleArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getComboArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getNodebuffArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getFistArena()) or $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getResistanceArena())) {
            $ev->setDrops([]);
            $ev->setDeathMessage("");
            $ev->getPlayer()->setGamemode(GameMode::ADVENTURE());
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $cause = $ev->getEntity()->getLastDamageCause();
            if ($cause instanceof EntityDamageByEntityEvent) {
                $damager = $cause->getDamager();
                if ($damager instanceof Player) {
                    foreach ([$damager, $player] as $p) {
                        $p->sendMessage(Core::getPrefix() . Color::RED . $player->getName() . Color::GRAY . " was killed by " . Color::GREEN . $damager->getName());
                    }
                    Core::getArena()->getReKit($damager);
                    $damager->setHealth($damager->getMaxHealth());
                }
            }
        }
    }

    public function onRespawn(PlayerRespawnEvent $ev)
    {
        $player = $ev->getPlayer();
        $player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
        $player->setGamemode(GameMode::ADVENTURE());
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $item = VanillaItems::IRON_SWORD()->setCustomName('§aPlay §f| §bClick to use')->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 10));
        $item2 = VanillaItems::BOW()->setCustomName('§bSettings §f| §bClick to use')->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 10));
        $player->getInventory()->setItem(8, $item2);
        $player->getInventory()->setItem(4, $item);
    }

    public function onDataPacketReceive(DataPacketReceiveEvent $event): void
    {
        $player = $event->getOrigin()->getPlayer();
        $packet = $event->getPacket();
        if ($packet instanceof InventoryTransactionPacket or $packet instanceof LevelSoundEventPacket) {
            if ($packet::NETWORK_ID === InventoryTransactionPacket::NETWORK_ID && $packet->trData instanceof UseItemOnEntityTransactionData or $packet::NETWORK_ID === LevelSoundEventPacket::NETWORK_ID && $packet->sound === LevelSoundEvent::ATTACK_NODAMAGE) {
                Core::getCPSCounter()->addClick($player);
            }
        }
    }
}
