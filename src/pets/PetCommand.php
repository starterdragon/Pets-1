<?php

namespace pets;
/**
 *All rights reserved JDNetwork
 *Do not redistribute this plugin!!
*/

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
	/*
	 * Lol, have fun!
	*/
	
	public function execute(CommandSender $sender, $currentAlias, array $args) {
	if($sender instanceof Player && !$sender->hasPermission("superpets") {
		if (!isset($args[0])) {
			$sender->sendMessage("§e======SuperPetsV3.0.7======");
			$sender->sendMessage("§b/pets generate [type] §eto spawn");
			$sender->sendMessage("§b/pets off §cto set your pet off");
			$sender->sendMessage("§a/pets changelog §dto get information about this plugin's fixes");
			$sender->sendMessage("§c/pets reload §areloads the pets plugin");
			$sender->sendMessage("§b/pets tag [name] §fto name your pet");
			$sender->sendMessage("§aPets: Dog, Rabbit, Pig, Cat, Chicken, Bat, Blaze, Cow, Enderman");
			$sender->sendMessage("§cAll rights reserved JDNetwork, This plugin was made by §eInspectorGadget");
			$sender->sendMessage(TF::RED . "Please use /pets off before generating a new pet");
			$sender->sendMessage("§aIf you have any problem, pls relog to fix it");
			$sender->sendMessage(TF::BLUE . "/pets clearall for OP and people with Perm");
			return true;
		}
		}
		switch (strtolower($args[0])){
			case "name":
			case "tag":
				if ($sender->sendMessage("superpets.tag")){
				if (isset($args[1])){
					unset($args[0]);
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag($name);
					$sender->sendMessage("Set Name to ".$name);
					$data = new Config($this->main->getDataFolder() . "players/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", $name); 
					$data->save();
				}
				}
				return true;
			break;
			case "reload";
				$this->main->disablePet($sender);
				$this->main->reload();
				$sender->sendMessage("§eYou have successfully reloaded SuperPets");
			break;
			case "changelog":
				$sender->sendMessage("§a--CHANGELOG--");
				$sender->sendMessage("Version 3.0.6. §aA neater version of SuperPets, removed ChangeLog from main /pets command, removed Ghast (produces lag), fixed some bugs, fixed the relationship between you and your pet. Get ready for more pets and features!");
			break;
			case "clearall":
				if ($sender->hasPermission("superpets.ca")) {
				$sender->sendMessage("Removed " . ($d = $this->main->removeMobs()) . " mob" . ($d == 1 ? "" : "s") . " and " . ($d = $this->main->removeEntities()) . " entit" . ($d == 1 ? "y" : "ies") . ".");
				}
				return true;
    			break;
			case "off":
				$this->main->disablePet($sender);
				$sender->sendMessage("§bYour pet has been cleared/turned off!");
				$sender->sendMessage("Hope you enjoy this pets plugin");
			break;
			case "generate":
				if (isset($args[1])){
					switch ($args[1]){
						case "Dog":
							$this->main->changePet($sender, "WolfPet");
							$pettype = "Dog";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Dog");
							$sender->sendMessage("All rights reserved JDNetwork");
							return true;
						break;
						case "Pig":
							$this->main->changePet($sender, "PigPet");
							$pettype = "Pig";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							$sender->sendMessage(TF::BLUE . "Your pet has changed to a Pig");
							$sender->sendMessage("All rights reserved JDNetwork");
							return true;
						break;
						case "Rabbit":
							$this->main->changePet($sender, "RabbitPet");
							$pettype = "Rabbit";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Rabbit");
							$sender->sendMessage("All rights reserved JDNetwork");
							return true;
						break;
						case "Cat":
							$this->main->changePet($sender, "OcelotPet");
							$pettype = "Cat";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Cat");
							$sender->sendMessage("All rights reserved JDNetwork");
							return true;
						break;
						case "Chicken":
							$this->main->changePet($sender, "ChickenPet");
							$pettype = "Chicken";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Chicken");
							$sender->sendMessage("All rights reserved JDNetwork");
							return true;
						break;
						case "Bat":
							$this->main->changePet($sender, "BatPet");
							$pettype = "Bat";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Bat");
							$sender->sendMessage("All rights reserved JDNetwork");
						break;
						case "Blaze":
							$this->main->changePet($sender, "BlazePet");
							$pettype = "Blaze";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Blaze");
							$sender->sendMessage("All rights reserved JDNetwork");
						break;
						case "Cow":
							$this->main->changePet($sender, "CowPet");
							$pettype = "Cow";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Cow");
							$sender->sendMessage("All rights reserved JDNetwork");
						break;
						case "Enderman":
							$this->main->changePet($sender, "EndermanPet");
							$pettype = "Enderman";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Enderman");
							$sender->sendMessage("All rights reserved JDNetwork");
						break;
					default:
						$sender->sendMessage("§b/pets generate [type]");
						$sender->sendMessage("§cPets: Dog, Rabbit, Pig, Cat, Chicken, Bat, Blaze, Cow, Enderman");
						$sender->sendMessage("§aHi IG here, please be more specific with the Capital letters!");
						$sender->sendMessage("All rights reserved JDNetwork, a standalone plugin made by InspectorGadget");
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
