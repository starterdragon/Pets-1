<?php

namespace pets;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pets\main;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

class PetCommand extends PluginCommand {

	public function __construct(main $main, $name) {
		parent::__construct(
				$name, $main
		);
		$this->main = $main;
		$this->setAliases(array("pets"));
	}

	public function execute(CommandSender $sender, $currentAlias, array $args) {
	if($sender->hasPermission("superpets")){
		if (!isset($args[0])) {
			$sender->sendMessage("§e======SuperPetsDropHelp======");
			$sender->sendMessage("§b/pets generate [type] to spawn");
			$sender->sendMessage("§b/pets off to set your pet off");
			$sender->sendMessage("§b/pets tag [name] name your pet");
			$sender->sendMessage("§4Pets: Dog, Rabbit, Pig, Cat, Chicken");
			$sender->sendMessage("§aAll rights reserved RTGNetwork, if youre using my plugin on a private network please ask me for a clean version");
			$sender->sendMessage(TF::RED . "Please use /pets off before generating a new pet");
			return true;
		}
		switch (strtolower($args[0])){
			case "name":
			case "tag":
				if (isset($args[1])){
					unset($args[0]);
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag($name);
					$sender->sendMessage("Set Name to ".$name);
					$data = new Config($this->main->getDataFolder() . "players/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", $name); 
					$data->save();
				}
				return true;
			break;
			case "help":
				$sender->sendMessage("§e======SuperPetsDropHelp======");
				$sender->sendMessage("§b/pets generate [type] to spawn");
				$sender->sendMessage("§b/pets off to set your pet off");
				$sender->sendMessage("§b/pets tag [name] name your pet");
				$sender->sendMessage("§bPets: Dog, Rabbit, Pig, Cat, Chicken");
				return true;
			break;
			case "off":
				$this->main->disablePet($sender);
				$sender->sendMessage("§bYour pet has been cleared/turned off!");
			break;
			case "generate":
				if (isset($args[1])){
					switch ($args[1]){
						case "Dog":
							$this->main->changePet($sender, "WolfPet");
							$pettype = "Dog";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							return true;
						break;
						case "Pig":
							$this->main->changePet($sender, "PigPet");
							$pettype = "Pig";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							return true;
						break;
						case "Rabbit":
							$this->main->changePet($sender, "RabbitPet");
							$pettype = "Rabbit";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							return true;
						break;
						case "Cat":
							$this->main->changePet($sender, "OcelotPet");
							$pettype = "Cat";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							return true;
						break;
						case "Chicken":
							$this->main->changePet($sender, "ChickenPet");
							$pettype = "Chicken";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							return true;
						break;
					default:
						$sender->sendMessage("§b/pets generate [type]");
						$sender->sendMessage("§cPets: Dog, Rabbit, Pig, Cat, Chicken");
						$sender->sendMessage("§aHi IG here, please be more specific with the Capital letters!");
					break;	
					return true;
					}
				}
			break;
		}
		return true;
	}
	}
}
