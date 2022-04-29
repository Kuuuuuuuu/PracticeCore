<?php

namespace NotZ\Execute;

use JsonException;
use NotZ\Core;
use NotZ\utils\Permissions;
use pocketmine\command\{Command, CommandSender};
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as Color;

class Commands extends Command
{

    public function __construct()
    {
        parent::__construct("core", "Core Commands");
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if ($sender instanceof Player) {
            if (!isset($args[0])) {
                $sender->sendMessage(Color::BOLD . Color::WHITE . "» " . Color::RESET . Color::RED . "use /core help");
                return false;
            }
            if (!$sender->hasPermission(DefaultPermissions::ROOT_OPERATOR)) {
                $sender->sendMessage(Color::BOLD . Color::WHITE . "» " . Color::RESET . Color::RED . "You don't have permission to use this command");
                return false;
            }
            switch ($args[0]) {
                case "help":
                    $sender->sendMessage(Color::YELLOW . "/" . $commandLabel . Color::BLUE . " make <mode> <world>" . Color::GOLD . " - create new Arena for FFA");
                    $sender->sendMessage(Color::YELLOW . "/" . $commandLabel . Color::BLUE . " spawn <mode>" . Color::GOLD . " - set spawn Arena for FFA");
                    $sender->sendMessage(Color::YELLOW . "/" . $commandLabel . Color::BLUE . " remove <mode>" . Color::GOLD . " - delete Arena for FFA");
                    $sender->sendMessage(Color::BOLD . "» " . Color::RESET . Color::YELLOW . "Modes: " . Color::GOLD . "gapple, Nodebuff, combo, fist, Resistance");
                    break;
                case "make":
                case "create":
                    if (!isset($args[1])) {
                        $sender->sendMessage(Core::getPrefix() . Color::RED . "use /core make <mode> <world>");
                        $sender->sendMessage(Color::YELLOW . "Modes: " . Color::GOLD . "gapple, Nodebuff, combo, fist, Resistance");
                        return false;
                    }
                    if (!isset($args[2])) {
                        $sender->sendMessage(Core::getPrefix() . Color::RED . "use /core make <mode> <world>");
                        $sender->sendMessage(Color::YELLOW . "Modes: " . Color::GOLD . "gapple, Nodebuff, combo, fist, Resistance");
                        return false;
                    }
                    switch ($args[1]) {
                        case "gapple":
                            if (!file_exists(Server::getInstance()->getDataPath() . "worlds/" . $args[2])) {
                                $sender->sendMessage(Core::getPrefix() . Color::BOLD . Color::WHITE . "» " . Color::RESET . Color::RED . "World " . $args[2] . " not found");
                            } else {
                                Server::getInstance()->getWorldManager()->loadWorld($args[2]);
                                $sender->teleport(Server::getInstance()->getWorldManager()->getWorldByName($args[2])->getSafeSpawn());
                                Core::getCreator()->setGappleArena($sender, $args[2]);
                            }
                            break;
                        case "Resistance":
                            if (!file_exists(Server::getInstance()->getDataPath() . "worlds/" . $args[2])) {
                                $sender->sendMessage(Core::getPrefix() . Color::BOLD . Color::WHITE . "» " . Color::RESET . Color::RED . "World " . $args[2] . " not found");
                            } else {
                                Server::getInstance()->getWorldManager()->loadWorld($args[2]);
                                $sender->teleport(Server::getInstance()->getWorldManager()->getWorldByName($args[2])->getSafeSpawn());
                                Core::getCreator()->setResistanceArena($sender, $args[2]);
                            }

                            break;
                        case "combo":
                            if (!file_exists(Server::getInstance()->getDataPath() . "worlds/" . $args[2])) {
                                $sender->sendMessage(Core::getPrefix() . Color::RED . "World " . $args[2] . " not found");
                            } else {
                                Server::getInstance()->getWorldManager()->loadWorld($args[2]);
                                $sender->teleport(Server::getInstance()->getWorldManager()->getWorldByName($args[2])->getSafeSpawn());
                                Core::getCreator()->setComboArena($sender, $args[2]);
                            }

                            break;
                        case "Nodebuff":
                            if (!file_exists(Server::getInstance()->getDataPath() . "worlds/" . $args[2])) {
                                $sender->sendMessage(Color::RED . "World " . $args[2] . " not found");
                            } else {
                                Server::getInstance()->getWorldManager()->loadWorld($args[2]);
                                $sender->teleport(Server::getInstance()->getWorldManager()->getWorldByName($args[2])->getSafeSpawn());
                                Core::getCreator()->setNodebuffArena($sender, $args[2]);
                            }
                            break;
                        case "fist":
                            if (!file_exists(Server::getInstance()->getDataPath() . "worlds/" . $args[2])) {
                                $sender->sendMessage(Color::RED . "World " . $args[2] . " not found");
                            } else {
                                Server::getInstance()->getWorldManager()->loadWorld($args[2]);
                                $sender->teleport(Server::getInstance()->getWorldManager()->getWorldByName($args[2])->getSafeSpawn());
                                Core::getCreator()->setFistArena($sender, $args[2]);
                            }

                            break;

                        default:
                            $sender->sendMessage(Core::getPrefix() . Color::RED . "use /core make <mode> <world>");
                            $sender->sendMessage(Color::YELLOW . "Modes: " . Color::GOLD . "gapple, Nodebuff, combo, fist, Resistance");
                            break;
                    }
                    break;
                case "spawn":
                    if (!isset($args[1])) {
                        $sender->sendMessage(Core::getPrefix() . Color::RED . "use /core spawn <mode>");
                        $sender->sendMessage(Color::YELLOW . "Modes: " . Color::GOLD . "gapple, Nodebuff, combo, fist, Resistance");
                        return false;
                    }
                    switch ($args[1]) {
                        case "gapple":
                            $arena = Core::getCreator()->getGappleArena();
                            if ($arena != null) {
                                Core::getCreator()->setGappleSpawn($sender);
                            } else {
                                $sender->sendMessage(Core::getPrefix() . Color::RED . "The Gapple world has not registered");
                                $sender->sendMessage(Color::RED . "use /core make <mode>");
                            }

                            break;
                        case "Resistance":
                            $arena = Core::getCreator()->getResistanceArena();
                            if ($arena != null) {
                                Core::getCreator()->setResistanceSpawn($sender);
                            } else {
                                $sender->sendMessage(Core::getPrefix() . Color::RED . "The Resistance world has not registered");
                                $sender->sendMessage(Color::RED . "use /core make <mode>");
                            }
                            break;
                        case "combo":
                            $arena = Core::getCreator()->getComboArena();
                            if ($arena != null) {
                                Core::getCreator()->setComboSpawn($sender);
                            } else {
                                $sender->sendMessage(Core::getPrefix() . Color::RED . "The Combo world has not registered");
                                $sender->sendMessage(Color::RED . "use /core make <mode>");
                            }

                            break;
                        case "Nodebuff":
                            $arena = Core::getCreator()->getNodebuffArena();
                            if ($arena != null) {
                                Core::getCreator()->setNodebuffSpawn($sender);
                            } else {
                                $sender->sendMessage(Core::getPrefix() . Color::RED . "The Nodebuff world has not registered");
                                $sender->sendMessage(Color::RED . "use /core make <mode>");
                            }

                            break;
                        case "fist":
                            $arena = Core::getCreator()->getFistArena();
                            if ($arena != null) {
                                Core::getCreator()->setFistSpawn($sender);
                            } else {
                                $sender->sendMessage(Core::getPrefix() . Color::RED . "The Fist world has not registered");
                                $sender->sendMessage(Color::RED . "use /core make <mode>");
                            }

                            break;
                        default:
                            $sender->sendMessage(Core::getPrefix() . Color::RED . "use /core spawn <mode>");
                            $sender->sendMessage(Color::YELLOW . "Modes: " . Color::GOLD . "gapple, Nodebuff, combo, fist, Resistance");
                            break;
                    }
                    break;
                case "remove":
                    if (!isset($args[1])) {
                        $sender->sendMessage(Core::getPrefix() . Color::RED . "use /core remove <mode>");
                        $sender->sendMessage(Color::YELLOW . "Modes: " . Color::GOLD . "gapple, Nodebuff, combo, fist, Resistance");
                        return false;
                    }
                    switch ($args[1]) {
                        case "gapple":
                            Core::getCreator()->removeGapple($sender);
                            break;
                        case "combo":
                            Core::getCreator()->removeCombo($sender);
                            break;
                        case "Nodebuff":
                            Core::getCreator()->removeNodebuff($sender);
                            break;
                        case "fist":
                            Core::getCreator()->removeFist($sender);
                            break;
                        case "Resistance":
                            Core::getCreator()->removeResistance($sender);
                            break;
                        default:
                            $sender->sendMessage(Core::getPrefix() . Color::RED . "use /core remove <mode>");
                            $sender->sendMessage(Color::YELLOW . "Modes: " . Color::GOLD . "gapple, Nodebuff, combo, fist, Resistance");
                            break;
                    }
                    break;
            }
        }
        return true;
    }
}