<?php

namespace Kuu\Arena;

use JsonException;
use Kuu\Core;
use pocketmine\player\Player;
use pocketmine\utils\{Config, TextFormat as Color};

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

    public function getNodebuffArena()
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        return $data->get("Nodebuff");
    }

    public function getNodebuffSpawn()
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        return $data->get("Nodebuff-Spawn");
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
        $player->sendMessage(Core::getPrefix() . "Gapple Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
        $player->sendMessage(Core::getPrefix() . "use /core spawn gapple - to select the spawn");

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
        $player->sendMessage(Core::getPrefix() . "Gapple spawn saved successfully");
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
        $player->sendMessage(Core::getPrefix() . "Gapple removed arena");
    }

    /**
     * @throws JsonException
     */
    public function setComboArena(Player $player, string $world): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->set("Combo", $world);
        $data->save();
        $player->sendMessage(Core::getPrefix() . "Combo Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
        $player->sendMessage(Core::getPrefix() . "use /core spawn combo - to select the spawn");

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
        $player->sendMessage(Core::getPrefix() . "Combo spawn saved successfully");
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
        $player->sendMessage(Core::getPrefix() . "Combo removed arena");
    }

    /**
     * @throws JsonException
     */
    public function setNodebuffArena(Player $player, string $world): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->set("Nodebuff", $world);
        $data->save();
        $player->sendMessage(Core::getPrefix() . "Nodebuff Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
        $player->sendMessage(Core::getPrefix() . "use /core spawn Nodebuff - to select the spawn");

    }

    /**
     * @throws JsonException
     */
    public function setNodebuffSpawn(Player $player): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $x = $player->getPosition()->getX();
        $y = $player->getPosition()->getY();
        $z = $player->getPosition()->getZ();
        $xyz = array($x, $y, $z);
        $data->set("Nodebuff-Spawn", $xyz);
        $data->save();
        $player->sendMessage(Core::getPrefix() . "Nodebuff spawn saved successfully");
    }

    /**
     * @throws JsonException
     */
    public function removeNodebuff(Player $player): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->remove("Nodebuff");
        $data->remove("Nodebuff-Spawn");
        $data->save();
        $player->sendMessage(Core::getPrefix() . "Nodebuff removed arena");
    }

    /**
     * @throws JsonException
     */
    public function setFistArena(Player $player, string $world): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->set("Fist", $world);
        $data->save();
        $player->sendMessage(Core::getPrefix() . "Fist Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
        $player->sendMessage(Core::getPrefix() . "use /core spawn fist - to select the spawn");

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
        $player->sendMessage(Core::getPrefix() . "Fist spawn saved successfully");
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
        $player->sendMessage(Core::getPrefix() . "Fist removed arena");
    }

    /**
     * @throws JsonException
     */
    public function setResistanceArena(Player $player, string $world): void
    {
        $data = new Config (Core::getInstance()->getDataFolder() . "data/arenas.yml", Config::YAML);
        $data->set("Resistance", $world);
        $data->save();
        $player->sendMessage(Core::getPrefix() . "Resistance Arena: " . Color::YELLOW . $world . Color::GREEN . " saved successfully");
        $player->sendMessage(Core::getPrefix() . "use /core spawn Resistance - to select the spawn");

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
        $player->sendMessage(Core::getPrefix() . "Resistance spawn saved successfully");
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
        $player->sendMessage(Core::getPrefix() . "Resistance removed arena");
    }
}