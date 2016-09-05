<?php

namespace pets;

use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
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

        /*
             This plugin is not stolen or whatsoever! This plugin was fully made by InspectorGadget. No license needed
             Website @rtgnetwork.tk
             Email: rtg@rtgnetwork.tk
        */

	public static $pet;
	public static $petState;
	public $petType;
	public $wishPet;
	public static $isPetChanging;
	public static $type;
	
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
		$this->saveDefaultConfig();
		$this->getServer()->getLogger()->info(TextFormat::BLUE . "SuperPets has been connected to database");
		$this->getServer()->getLogger()->info(TextFormat::BLUE . "http://github.com/RTGThePlayer");
		$this->getServer()->getLogger()->info(TextFormat::RED . "Copyrights RTGNetwork, all rights reserved");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
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
 				default:
 					$pets = array("OcelotPet", "PigPet", "WolfPet",  "RabbitPet", "ChickenPet");
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
		$data = new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml", Config::YAML);
		if($data->exists("type")){ 
			$type = $data->get("type");
			$this->changePet($player, $type);
		}
		if($data->exists("name")){ 
			$name = $data->get("name");
			$this->getPet($player->getName())->setNameTag($name);
		}
	}
	
	public function onDisable() {
		$sender->getServer->getLogger()->info("Disabling SuperPets by RTGNetwork");
	}
}
