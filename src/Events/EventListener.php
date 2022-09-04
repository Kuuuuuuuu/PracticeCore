<?php

namespace Kuu\Events;

use Kuu\Core;
use Kuu\utils\FormAPI\CustomForm;
use Kuu\utils\FormAPI\SimpleForm;
use pocketmine\entity\projectile\SplashPotion;
use pocketmine\event\block\{BlockBreakEvent, BlockPlaceEvent};
use pocketmine\event\entity\{EntityDamageByEntityEvent, EntityDamageEvent, ProjectileHitBlockEvent};
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerDeathEvent,
    PlayerDropItemEvent,
    PlayerExhaustEvent,
    PlayerItemUseEvent,
    PlayerRespawnEvent
};
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\EnderPearl;
use pocketmine\item\PotionType;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as Color;

class EventListener implements Listener
{
    private array $pearlcd = [];
    private array $formplayer = [];

    public function onUseItem(PlayerItemUseEvent $e): void
    {
        $player = $e->getPlayer();
        $item = $e->getItem();
        if ($item instanceof EnderPearl) {
            $cooldown = 10;
            $player = $e->getPlayer();
            if (isset($this->pearlcd[$player->getName()]) && time() - $this->pearlcd[$player->getName()] < $cooldown) {
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

    public function mForm($player): void
    {
        $arr = [];
        foreach (Server::getInstance()->getOnlinePlayers() as $players) {
            $arr[] = $players->getName();
        }
        $this->formplayer[$player->getName()] = $arr;
        $form = new SimpleForm(function (Player $player, $data) {
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $form = new CustomForm(function (Player $player, array $data = null) {
                        $result = $data;
                        if ($result === null) {
                            return;
                        }
                        foreach (Server::getInstance()->getOnlinePlayers() as $players) {
                            if ($players->hasPermission(DefaultPermissions::ROOT_OPERATOR)) {
                                $player->sendMessage("§4§l[!] §r§a{$this->formplayer[$player->getName()][$result[0]]} §r§ahas reported by {$player->getName()} §6Reason: §r§a$result[1]");
                            }
                        }
                        $player->sendMessage(Core::getPrefix() . "§aYou have reported {$this->formplayer[$player->getName()][$result[0]]}");
                    });
                    $form->setTitle(Core::getPluginName() . " §6Report Name");
                    $form->addDropdown("§bSelect a name", $this->formplayer[$player->getName()]);
                    $form->addInput(Color::RED . "Reason");
                    $player->sendForm($form);
                    break;
                case 1:
                    $form = new SimpleForm(function (Player $player, int $data = null) {
                        $result = $data;
                        if ($result === null) {
                            return;
                        }
                        switch ($result) {
                            case 0:
                                $form = new CustomForm(function (Player $player, array $data = null) {
                                    $result = $data;
                                    if ($result === null) {
                                        return;
                                    }
                                    $name = $result[0];
                                    $player->setDisplayName($name);
                                    $player->setNameTag($name);
                                    $player->sendMessage(Core::getPrefix() . "Your name has been changed to " . $name);
                                });
                                $form->setTitle(Color::GREEN . "Change your name");
                                $form->addInput(Color::GREEN . "Your name");
                                $player->sendForm($form);
                                break;
                            case 1:
                                $player->setDisplayName($player->getName());
                                $player->setNameTag($player->getName());
                                $player->sendMessage(Core::getPrefix() . "Your name has been changed to " . $player->getName());
                                break;
                        }
                    });
                    $form->setTitle(Core::getPluginName() . " §bSettings");
                    $form->addButton("§6Change §fName");
                    $form->addButton("§cReset §fName");
                    $form->sendToPlayer($player);
                    break;
            }
        });
        $form->setTitle(Core::getPluginName() . " §eSettings");
        $form->addButton("§6Reports §fPlayers", 0, "textures/items/iron_sword");
        $form->addButton("§6Change §fName", 0, "textures/items/snowball");
        $player->sendForm($form);
    }

    public function onHit(ProjectileHitBlockEvent $event): void
    {
        $projectile = $event->getEntity();
        if ($projectile instanceof SplashPotion && $projectile->getPotionType() === PotionType::STRONG_HEALING()) {
            $player = $projectile->getOwningEntity();
            if ($player instanceof Player && $player->isAlive() && $projectile->getPosition()->distance($player->getPosition()) <= 3) {
                $player->setHealth($player->getHealth() + 3.5);
            }
        }
    }

    public function onExhaust(PlayerExhaustEvent $event): void
    {
        $player = $event->getPlayer();
        if ($player->getHungerManager()->getFood() <= 20) {
            $player->getHungerManager()->setFood(20);
        }
    }

    public function PlayerJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $event->setJoinMessage("§7[§a+§7] §f" . $player->getName());
        $player->setGamemode(GameMode::ADVENTURE());
        $player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()?->getSafeSpawn());
        Core::getCPSCounter()->initPlayerClickData($player);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getOffHandInventory()->clearAll();
        Core::getCoreUtils()->getLobbyItem($player);
    }

    public function PlayerQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();
        $event->setQuitMessage("§f[§c-§f] §r" . $player->getDisplayName());
        $player->setGamemode(GameMode::ADVENTURE());
        $player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()?->getSafeSpawn());
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getOffHandInventory()->clearAll();
        Core::getCPSCounter()->removePlayerClickData($player);
        $player->kill();
    }

    public function onBreak(BlockBreakEvent $ev): void
    {
        $player = $ev->getPlayer();
        if (!$player->hasPermission(DefaultPermissions::ROOT_OPERATOR) && $player->getGamemode() !== GameMode::CREATIVE()) {
            $ev->cancel();
        }
    }

    public function onPlace(BlockPlaceEvent $ev): void
    {
        $player = $ev->getPlayer();
        if (!$player->hasPermission(DefaultPermissions::ROOT_OPERATOR) && $player->getGamemode() !== GameMode::CREATIVE()) {
            $ev->cancel();
        }
    }

    public function onDrop(PlayerDropItemEvent $ev): void
    {
        $ev->cancel();
    }

    public function onDamageFall(EntityDamageEvent $ev): void
    {
        $player = $ev->getEntity();
        if ($player instanceof Player) {
            if ($player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getGappleArena()) || $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getComboArena()) || $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getNodebuffArena()) || $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getFistArena()) || $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getResistanceArena()) || $player->getWorld() === Server::getInstance()->getWorldManager()->getDefaultWorld()) {
                if ($ev->getCause() === EntityDamageEvent::CAUSE_FALL) {
                    $ev->cancel();
                }
            }
        }
    }

    public function onDeath(PlayerDeathEvent $ev): void
    {
        $player = $ev->getPlayer();
        if ($player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getGappleArena()) || $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getComboArena()) || $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getNodebuffArena()) || $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getFistArena()) || $player->getWorld() === Server::getInstance()->getWorldManager()->getWorldByName(Core::getCreator()->getResistanceArena())) {
            $ev->setDrops([]);
            $ev->setDeathMessage("");
            $ev->getPlayer()->setGamemode(GameMode::ADVENTURE());
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $player->getOffHandInventory()->clearAll();
            $cause = $ev->getEntity()->getLastDamageCause();
            if ($cause instanceof EntityDamageByEntityEvent) {
                $damager = $cause->getDamager();
                if ($damager instanceof Player) {
                    foreach ([$damager, $player] as $p) {
                        $p->sendMessage(Core::getPrefix() . $player->getName() . Color::GRAY . " was killed by " . Color::GREEN . $damager->getName());
                    }
                    Core::getArena()->getReKit($damager);
                    $damager->setHealth($damager->getMaxHealth());
                }
            }
        }
    }

    public function onRespawn(PlayerRespawnEvent $ev): void
    {
        $player = $ev->getPlayer();
        $player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()?->getSafeSpawn());
        $player->setGamemode(GameMode::ADVENTURE());
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        Core::getCoreUtils()->getLobbyItem($player);
    }

    public function onDataPacketReceive(DataPacketReceiveEvent $event): void
    {
        $player = $event->getOrigin()->getPlayer();
        $packet = $event->getPacket();
        if ($packet instanceof InventoryTransactionPacket || $packet instanceof LevelSoundEventPacket) {
            if (($packet::NETWORK_ID === InventoryTransactionPacket::NETWORK_ID && $packet->trData instanceof UseItemOnEntityTransactionData) || ($packet::NETWORK_ID === LevelSoundEventPacket::NETWORK_ID && $packet->sound === LevelSoundEvent::ATTACK_NODAMAGE)) {
                if ($player instanceof Player) {
                    Core::getCPSCounter()->addClick($player);
                }
            }
        }
    }
}
