<?php


namespace Kuu\utils;


use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class Utils
{
    public function getLobbyItem(Player $player): void
    {
        $player->getInventory()->setItem(4, VanillaItems::DIAMOND_SWORD()->setCustomName("§aPlay §f| §bClick to use"));
        $player->getInventory()->setItem(8, VanillaItems::IRON_SWORD()->setCustomName("§bSettings §f| §bClick to use"));
    }

    public function playSound(Player $player, string $soundName, int $pitch = 1, int $volumen = 20): void
    {
        $pk = new PlaySoundPacket();
        $pk->soundName = $soundName;
        $pk->volume = $volumen;
        $pk->pitch = $pitch;
        $pk->x = $player->getPosition()->getX();
        $pk->y = $player->getPosition()->getY();
        $pk->z = $player->getPosition()->getZ();
        $player->getNetworkSession()->sendDataPacket($pk);
    }
}