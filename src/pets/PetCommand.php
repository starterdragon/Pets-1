<?php

namespace pets;

/*
 * Got'em
 * InspectorGadget
 * Please do not redistribute this plugin under a different name!
 * Justice will be served for people who copy or redistibute w/o proper rights!
 * All rights reserved JDNetwork, a standalone Pocketmine plugin for Imagicalmine, Genisys && Pocketmine
 * Might not function properly on MCPE 16.0 as it breaks "/" command code
*/

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pets\main;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use pocketmine\Server;

class PetCommand extends PluginCommand {

	public function __construct(main $main, $name) {
		parent::__construct(
				$name, $main
		);
		$this->main = $main;
		$this->plugin = $plugin;
		$this->setAliases(array("pets"));
	}

	public function execute(CommandSender $sender, $currentAlias, array $args) {
		if(!$sender->hasPermission("superpets") && $sender instanceof Player){
			$sender->sendMessage(TF::RED . "You have no permission to use this command!");
		return true;
		}
		if (!isset($args[0])) {
			$sender->sendMessage("§e======SuperPetsV3.1.4=====");
			$sender->sendMessage("                  ");
			$sender->sendMessage(TF::RED . "Hi there, " . TF::ITALIC . TF::YELLOW . $sender->getName());
			$sender->sendMessage("§e~~~~~~~~~~~~~");
			$sender->sendMessage("§b/pets generate [type] §ato spawn");
			$sender->sendMessage("§b/pets off §ato set your pet off");
			$sender->sendMessage("§b/pets about §atells about this plugin!");
			$sender->sendMessage("§b/pets changelog §ato get information about this plugin's fixes");
			$sender->sendMessage("§b/pets reload §creloads the pets plugin");
			$sender->sendMessage("§b/pets tag [name] §ato name your pet");
			$sender->sendMessage("§ePets: Dog, Rabbit, Pig, Cat, Chicken, Bat, Blaze, Cow, Enderman, Sheep, WhiteRabbit, BrownRabbit, Zombie, Horse, Donkey, Mule, SkeletonHorse, ZombieHorse");
			$sender->sendMessage("§cAll rights reserved JDNetwork, This plugin was made by §eInspectorGadget");
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
			case "reload";
				if (!$sender->hasPermission("superload")) {
					$sender->sendMessage(TF::RED . "You have no permission to use this command!");
				return true;
				}
				$this->main->removeMobs();
				$this->main->disablePet($sender);
				$this->main->reload();
				$sender->sendMessage(TF::RED . $sender->getName() . TF::YELLOW . " have reloaded SuperPets!");
				return true;
			break;
			case "changelog":
				$sender->sendMessage("§a--CHANGELOG--");
				$sender->sendMessage("Version 3.1.4" . TF::RED . " Added Horse, Donkey, Mule, ZombieHorse & SkeletonHorse, added auto pet /tell message && more to come!!. Added a system where it autocorrects a bad code..");
				$sender->sendMessage("Version 3.1.3" . TF::GREEN . " updated to API 2.1.0 which supports MCPE 16.0!");
				$sender->sendMessage("Version 3.1.2" . TF::YELLOW . " Added No-Perm message for /pets reload which is requested by few ServerOwners!" . TF::RED . " Its all customizable on config.yml! Check it out, more to come! Horses? maybe :)");
			break;
			case "off":
				$this->main->disablePet($sender);
				$this->main->reload();
				$this->main->removeMobs();
				$sender->sendMessage("§bYour pet has been cleared/turned off!");
				$sender->sendMessage("Hope you enjoy this pets plugin");
				$sender->sendMessage(TF::RED . "PET: " . TF::YELLOW . "Bye, " . TF::AQUA . $sender->getName());
			break;
			case "about":
				$sender->sendMessage(TF::ITALIC . TF::RED . " Name: SuperPets");
				$sender->sendMessage(TF::ITALIC . TF::GREEN . " Version: 3.1.4");
				$sender->sendMessage(TF::ITALIC . "Description: People has been dreaming about pets for ages, now its a reality!");
			break;
			case "generate":
				if (isset($args[1])){
					switch ($args[1]){
						case "Dog":
							$this->main->changePet($sender, "WolfPet");
							$pettype = "Dog";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Dog!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Pig":
							$this->main->changePet($sender, "PigPet");
							$pettype = "Pig";
							$sender->sendMessage(TF::BLUE . "Your let has been changed to a Pig!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Rabbit":
							$this->main->changePet($sender, "RabbitPet");
							$pettype = "Rabbit";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Rabbit!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Cat":
							$this->main->changePet($sender, "OcelotPet");
							$pettype = "Cat";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Cat!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Chicken":
							$this->main->changePet($sender, "ChickenPet");
							$pettype = "Chicken";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Chicken!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Bat":
							$this->main->changePet($sender, "BatPet");
							$pettype = "Bat";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Bat!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Blaze":
							$this->main->changePet($sender, "BlazePet");
							$pettype = "Blaze";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Blaze!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Cow":
							$this->main->changePet($sender, "CowPet");
							$pettype = "Cow";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Cow!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Enderman":
							$this->main->changePet($sender, "EndermanPet");
							$pettype = "Enderman";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Enderman!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Sheep":
							$this->main->changePet($sender, "SheepPet");
							$pettype = "Sheep";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Sheep!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Zombie":
							$this->main->changePet($sender, "ZombiePet");
							$pettype = "Zombie";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Zombie!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "WhiteRabbit":
							$this->main->changePet($sender, "WhiteRabbitPet");
							$pettype = "WhiteRabbit";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a WhiteRabbit!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "BrownRabbit":
							$this->main->changePet($sender, "BrownRabbitPet");
							$pettype = "BrownRabbit";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a BrownRabbit!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Horse":
							$this->main->changePet($sender, "HorsePet");
							$pettype = "HorsePet";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Horse!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Donkey":
							$this->main->changePet($sender, "DonkeyPet");
							$pettype = "Donkey";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a Donkey!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "Mule":
							$this->main->changePet($sender, "MulePet");
							$pettype = "Mule";
							$sender->sendMessage(TF::BLUE . "You pet has been changed to a Mule!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "SkeletonHorse":
							$this->main->changePet($sender, "SkeletonHorsePet");
							$pettype = "SkeletonHorse";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a SkeletonHorse!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
						case "ZombieHorse":
							$this->main->changePet($sender, "ZombieHorsePet");
							$pettype = "ZombieHorse";
							$sender->sendMessage(TF::BLUE . "Your pet has been changed to a ZombieHorse!");
							$sender->sendMessage($this->main->getConfig()->get("rights-msg"));
							return true;
						break;
					default:
						$sender->sendMessage("§b/pets generate [type]");
						$sender->sendMessage("§cPets: Dog, Rabbit, Pig, Cat, Chicken, Bat, Blaze, Cow, Enderman, BrownRabbit, Zombie, WhiteRabbit, Sheep, Horse, Donkey, Mule, SkeletonHorse, ZombieHorse");
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