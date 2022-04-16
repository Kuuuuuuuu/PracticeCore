<?php

namespace NotZ\Execute;

use NotZ\Core;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\enchantment\{EnchantmentInstance, VanillaEnchantments};
use pocketmine\item\VanillaItems;
use pocketmine\player\GameMode;
use pocketmine\player\Player;


class HUB extends Command
{

    public function __construct()
    {
        parent::__construct("hub", "back to spawn", null, ["spawn", "lobby"]);
    }

    public function execute(CommandSender $sender, string $commandCommandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $sender->getInventory()->clearAll();
            $sender->getArmorInventory()->clearAll();
            $sender->getEffects()->clear();
            $sender->setGamemode(GameMode::ADVENTURE());
            $sender->setScale(1);
            $sender->setAllowFlight(false);
            $sender->teleport(Core::getInstance()->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
            $sender->sendMessage("§cBerry §f>> §bWelcome to Spawn§e " . $sender->getName());
            $item = VanillaItems::IRON_SWORD()->setCustomName('§aPlay §f| §bClick to use')->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 10));
            $item2 = VanillaItems::BOW()->setCustomName('§bSettings §f| §bClick to use')->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 10));
            $sender->getInventory()->setItem(8, $item2);
            $sender->getInventory()->setItem(4, $item);
        }
    }
}

