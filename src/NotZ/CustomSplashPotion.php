<?php

namespace NotZ;

use pocketmine\entity\Location;
use pocketmine\entity\projectile\SplashPotion as SplashPotionEntity;
use pocketmine\entity\projectile\Throwable;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\PotionType;
use pocketmine\item\ProjectileItem;
use pocketmine\player\Player;

class CustomSplashPotion extends ProjectileItem
{

    private PotionType $potionType;

    public function __construct(ItemIdentifier $identifier, string $name, PotionType $potionType)
    {
        parent::__construct($identifier, $name);
        $this->potionType = $potionType;
    }

    public function getType(): PotionType
    {
        return $this->potionType;
    }

    public function getMaxStackSize(): int
    {
        return 1;
    }

    public function getThrowForce(): float
    {
        return 0;
    }

    protected function createEntity(Location $location, Player $thrower): Throwable
    {
        return new SplashPotionEntity(new Location($thrower->getPosition()->getX(), $thrower->getPosition()->getY(), $thrower->getPosition()->getZ(), $thrower->getLocation()->getWorld(), 0, 0), $thrower, $this->potionType);
    }
}
