<?php

namespace NotZ\utils\Entities;

use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\math\Vector3;
use pocketmine\Player;

use NotZ\Core;

class CoreManager {
	
	public function setJoinEntity(Player $player) {
		$player->saveNBT();
		$nbt = Entity::createBaseNBT(new Vector3((float)$player->getX(), (float)$player->getY(), (float)$player->getZ()));
		$nbt->setTag(clone $player->namedtag->getCompoundTag("Skin"));
		$human = new JoinCore($player->getLevel(), $nbt);
		$human->setNameTagVisible(true);
		$human->setNameTagAlwaysVisible(true);
		$human->yaw = $player->getYaw();
		$human->pitch = $player->getPitch();
		$human->spawnToAll();
	}
	
	public function setStatus(Player $player) {
		$nbt = Entity::createBaseNBT(new Vector3((float)$player->getX(), (float)$player->getY(), (float)$player->getZ()));
		$nbt->setTag($player->namedtag->getTag('Skin'));
		$human = new CoreStatus($player->getLevel(), $nbt);
		$human->setSkin(new Skin('textfloat', $human->getInvisibleSkin()));
		$human->setNameTagVisible(true);
		$human->setNameTagAlwaysVisible(true);
		$human->spawnToAll();
	}
	
}