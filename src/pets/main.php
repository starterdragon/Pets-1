<?php

namespace pets;

/*
 * Got'em
 * InspectorGadget
 * Please do not redistribute this plugin under a different name!
 * Justice will be served for people who copy or redistibute w/o proper rights!
 * All rights reserved JDNetwork, a standalone Pocketmine plugin for Imagicalmine, Genisys && Pocketmine.
*/
 
use pets\task\PetsTick;

use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\entity\DroppedItem;
use pocketmine\entity\Human;
use pocketmine\entity\Creature;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pets\PetCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;

class main extends PluginBase implements Listener {
	
	public static $pet;
	public static $petState;
	public $petType;
	public $wishPet;
	public static $isPetChanging;
	public static $type;
	
	const MAIN_PREFIX = "[SP]";
	
	public function onEnable() {
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder() . "players");
		$server = Server::getInstance();
		$server->getCommandMap()->register('pets', new PetCommand($this,"pets"));
		Entity::registerEntity(OcelotPet::class);
		Entity::registerEntity(WolfPet::class);
		Entity::registerEntity(PigPet::class);
		Entity::registerEntity(RabbitPet::class);
		Entity::registerEntity(ChickenPet::class);
		Entity::registerEntity(BatPet::class);
		Entity::registerEntity(EndermanPet::class);
		Entity::registerEntity(BlazePet::class);
		Entity::registerEntity(CowPet::class);
		Entity::registerEntity(BrownRabbitPet::class);
		Entity::registerEntity(ZombiePet::class);
		Entity::registerEntity(WhiteRabbitPet::class);
		Entity::registerEntity(SheepPet::class);
		Entity::registerEntity(HorsePet::class);
		Entity::registerEntity(DonkeyPet::class);
		Entity::registerEntity(MulePet::class);
		Entity::registerEntity(ZombieHorsePet::class);
		Entity::registerEntity(SkeletonHorsePet::class);
		$this->saveDefaultConfig();
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new PetsTick($this), 20); // Messages runner! PHP HACKX
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->warning("
		* Name: SuperPets
		* Version: 3.1.4
		* website: www.github.com/RTGThePlayer
		* API: 2.1.0, 2.0.0
		* Author: InspectorGadget
		* Contribution: JDNetwork
		* MySQL: true
		* Connection status: ACTIVE 4ms
		* STATUS: Connected
		");
	}

	public function create($player,$type, Position $source, ...$args) {
		$chunk = $source->getLevel()->getChunk($source->x >> 4, $source->z >> 4, true);
		$nbt = new CompoundTag("", [
			"Pos" => new ListTag("Pos", [
				new DoubleTag("", $source->x),
				new DoubleTag("", $source->y),
				new DoubleTag("", $source->z)
					]),
			"Motion" => new ListTag("Motion", [
				new DoubleTag("", 0),
				new DoubleTag("", 0),
				new DoubleTag("", 0)
					]),
			"Rotation" => new ListTag("Rotation", [
				new FloatTag("", $source instanceof Location ? $source->yaw : 0),
				new FloatTag("", $source instanceof Location ? $source->pitch : 0)
					]),
		]);
		$pet = Entity::createEntity($type, $chunk, $nbt, ...$args);
		$data = new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml", Config::YAML);
		$data->set("type", $type); 
		$data->save();
		$pet->setOwner($player);
		$pet->spawnToAll();
		return $pet; 
	}

	public function createPet(Player $player, $type, $holdType = "") {
 		if (isset($this->pet[$player->getName()]) != true) {	
			$len = rand(8, 12); 
			$x = (-sin(deg2rad($player->yaw))) * $len  + $player->getX();
			$z = cos(deg2rad($player->yaw)) * $len  + $player->getZ();
			$y = $player->getLevel()->getHighestBlockAt($x, $z);

			$source = new Position($x , $y + 2, $z, $player->getLevel());
			if (isset(self::$type[$player->getName()])){
				$type = self::$type[$player->getName()];
			}
 			switch ($type){
 				case "WolfPet":
 				break;
 				case "RabbitPet":
 				break;
 				case "PigPet":
 				break;
 				case "OcelotPet":
 				break;
 				case "ChickenPet":
 				break;
 				case "BatPet":
 				break;
 				case "BlazePet":
 				break;
 				case "CowPet":
 				break;
 				case "EndermanPet":
				break;
				case "SheepPet":
				break;
				case "BrownRabbitPet":
				break;
				case "WhiteRabbitPet":
				break;
				case "ZombiePet":
 				break;
 				case "HorsePet":
 				break;
 				case "DonkeyPet":
 				break;
 				case "MulePet":
 				break;
 				case "ZombieHorsePet":
 				break;
 				case "SkeletonHorsePet":
 				break;
 				default:
 					$pets = array("OcelotPet", "PigPet", "WolfPet",  "RabbitPet", "ChickenPet", "BatPet", "BlazePet", "CowPet", "EndermanPet, BrownRabbitPet, ZombiePet, WhiteRabbitPet, SheepPet, HorsePet, DonkeyPet, MulePet, ZombieHorsePet, SkeletonHorsePet");
 					$type = $pets[rand(0, 5)];
 			}
			$pet = $this->create($player,$type, $source);
			return $pet;
 		}
	}

	public function onPlayerQuit(PlayerQuitEvent $event) {
		$player = $event->getPlayer();
		$this->disablePet($player);
	}
	
	public function disablePet(Player $player) {
		if (isset(self::$pet[$player->getName()])) {
			self::$pet[$player->getName()]->close();
			self::$pet[$player->getName()] = null;
		}
	}
	
	public function changePet(Player $player, $newtype){
		$type = $newtype;
		$this->disablePet($player);
		self::$pet[$player->getName()] = $this->createPet($player, $newtype);
	}
	
	public function getPet($player) {
		return self::$pet[$player];
	}
	
	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$this->disablePet($player);
	}

	public function reload() {
		$this->reloadConfig();
		$this->saveDefaultConfig();
		$this->getServer()->getLogger()->info("done reloading " . $this->getName());
	}
	
	public function removeMobs() {
		$i = 0;
		foreach($this->getServer()->getLevels() as $level) {
			foreach($level->getEntities() as $entity) {
				if(!$this->isEntityExempted($entity) && $entity instanceof Creature &&!($entity instanceof Human)) {
					$entity->close();
					$i++;
				}
			}
		}
		return true;
	}
	
	public function exemptEntity(Entity $entity) {
		$this->exemptedEntities[$entity->getID()] = $entity;
	}
	
	public function isEntityExempted(Entity $entity) {
		return isset($this->exemptedEntities[$entity->getID()]);
	}
	
	public function onDisable() {
	}
	
	public function sendPetMessage(Player $player, $reason = 1) {
		$availReasons = array(
			1 => "SP_WELCOME",
			2 => "SP_GB",
			3 => "SP_RDM"
		);
		switch ($availReasons[$reason]) {
			case "SP_WELCOME":
				$messages = array(
					"Hey there Best Friend!",
					"Hi!",
					"Welcome Back!",
					"Where ya been?",
					"I love you :P"
				);
				break;
			case "SP_GB":
				$messages = array(
					"Bye!",
					"Bye Bye!",
					"see ya later!",
					"I'll Miss Ya!",
					"Don't leave me!"
				);
				break;
			case "SP_RDM": //neutral messages that can be said anytime
				$messages = array(
					"I'm Hungry, do you have any food?",
					"I'm gonna starve here... Please... Food!",
					"I smell food in your pocket! Can I have some?",
					"I'm gonna eat you if you don't feed me!",
					"I need food really bad, please!"
				);
				break;
			default: //same as random messages
				$messages = array(
					"I'm Hungry, do you have any food?",
					"I'm gonna starve here... Please... Food!",
					"I smell food in your pocket! Can I have some?",
					"I'm gonna eat you if you don't feed me!",
					"I need food really bad, please!"
				);
				break;
		}
		$message = $messages[rand(0, count($messages) - 1)];
		$player->sendMessage(SuperPets::MAIN_PREFIX . $this->getPet($player)->getNameTag() . TF::WHITE ." > " .TF::GRAY. $message);
	}
}