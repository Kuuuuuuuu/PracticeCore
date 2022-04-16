<?php

namespace NotZ\Arena;

use JsonException;
use NotZ\Core;
use pocketmine\utils\{Config, TextFormat as Color};
use pocketmine\player\Player;

class ArenaCreator
{

    public function getGappleArena()
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        return $data->get("Gapple");
    }

    public function getGappleSpawn()
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        return $data->get("Gapple-Spawn");
    }

    public function getComboArena()
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        return $data->get("Combo");
    }

    public function getComboSpawn()
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        return $data->get("Combo-Spawn");
    }

    public function getDebuffArena()
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        return $data->get("Debuff");
    }

    public function getDebuffSpawn()
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        return $data->get("Debuff-Spawn");
    }

    public function getResistanceArena()
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        return $data->get("Resistance");
    }

    public function getResistanceSpawn()
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        return $data->get("Resistance-Spawn");
    }

    public function getFistArena()
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        return $data->get("Fist");
    }

    public function getFistSpawn()
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        return $data->get("Fist-Spawn");
    }

    /**
     * @throws JsonException
     */
    public function setGappleArena(Player $player, string $world): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->set("Gapple", $world);
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GREEN . "Gapple Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GOLD . "use /core spawn gapple - to select the spawn");

    }

    /**
     * @throws JsonException
     */
    public function setGappleSpawn(Player $player): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $x = $player->getPosition()->getX();
        $y = $player->getPosition()->getY();
        $z = $player->getPosition()->getZ();
        $xyz = array($x, $y, $z);
        $data->set("Gapple-Spawn", $xyz);
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GREEN . "Gapple spawn saved successfully");
    }

    /**
     * @throws JsonException
     */
    public function removeGapple(Player $player): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->remove("Gapple");
        $data->remove("Gapple-Spawn");
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::RED . "Gapple removed arena");
    }

    /**
     * @throws JsonException
     */
    public function setComboArena(Player $player, string $world): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->set("Combo", $world);
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GREEN . "Combo Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GOLD . "use /core spawn combo - to select the spawn");

    }

    /**
     * @throws JsonException
     */
    public function setComboSpawn(Player $player): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $x = $player->getPosition()->getX();
        $y = $player->getPosition()->getY();
        $z = $player->getPosition()->getZ();
        $xyz = array($x, $y, $z);
        $data->set("Combo-Spawn", $xyz);
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GREEN . "Combo spawn saved successfully");
    }

    /**
     * @throws JsonException
     */
    public function removeCombo(Player $player): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->remove("Combo");
        $data->remove("Combo-Spawn");
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::RED . "Combo removed arena");
    }

    /**
     * @throws JsonException
     */
    public function setDebuffArena(Player $player, string $world): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->set("Debuff", $world);
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GREEN . "Debuff Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GOLD . "use /core spawn debuff - to select the spawn");

    }

    /**
     * @throws JsonException
     */
    public function setDebuffSpawn(Player $player): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $x = $player->getPosition()->getX();
        $y = $player->getPosition()->getY();
        $z = $player->getPosition()->getZ();
        $xyz = array($x, $y, $z);
        $data->set("Debuff-Spawn", $xyz);
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GREEN . "Debuff spawn saved successfully");
    }

    /**
     * @throws JsonException
     */
    public function removeDebuff(Player $player): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->remove("Debuff");
        $data->remove("Debuff-Spawn");
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::RED . "Debuff removed arena");
    }

    /**
     * @throws JsonException
     */
    public function setFistArena(Player $player, string $world): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->set("Fist", $world);
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GREEN . "Fist Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GOLD . "use /core spawn fist - to select the spawn");

    }

    /**
     * @throws JsonException
     */
    public function setFistSpawn(Player $player): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $x = $player->getPosition()->getX();
        $y = $player->getPosition()->getY();
        $z = $player->getPosition()->getZ();
        $xyz = array($x, $y, $z);
        $data->set("Fist-Spawn", $xyz);
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GREEN . "Fist spawn saved successfully");
    }

    /**
     * @throws JsonException
     */
    public function removeFist(Player $player): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->remove("Fist");
        $data->remove("Fist-Spawn");
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::RED . "Fist removed arena");
    }

    /**
     * @throws JsonException
     */
    public function setResistanceArena(Player $player, string $world): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->set("Resistance", $world);
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GREEN . "Resistance Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GOLD . "use /core spawn Resistance - to select the spawn");

    }

    /**
     * @throws JsonException
     */
    public function setResistanceSpawn(Player $player): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $x = $player->getPosition()->getX();
        $y = $player->getPosition()->getY();
        $z = $player->getPosition()->getZ();
        $xyz = array($x, $y, $z);
        $data->set("Resistance-Spawn", $xyz);
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::GREEN . "Resistance spawn saved successfully");
    }

    /**
     * @throws JsonException
     */
    public function removeResistance(Player $player): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->remove("Resistance");
        $data->remove("Resistace-Spawn");
        $data->save();
        $player->sendMessage(Core::getInstance()->getPrefix() . Color::RED . "Resistance removed arena");
    }
}