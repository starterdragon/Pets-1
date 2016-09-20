<?php

namespace pets;

use pocketmine\entity\DroppedItem;
use pocketmine\entity\Human;
use pocketmine\entity\Creature;
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
	
	public static $pet;
	public static $petState;
	public $petType;
	public $wishPet;
	public static $isPetChanging;
	public static $type;
	protected $exemptedEntities = [];
	
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
		$this->saveDefaultConfig();
		$this->getServer()->getLogger()->info(TextFormat::BLUE . "SuperPets has been connected to MySQL database");
		$this->getServer()->getLogger()->info(TextFormat::RED . "SuperPets V 3.0.7");
		$this->getServer()->getLogger()->info(TextFormat::BLUE . "http://github.com/RTGThePlayer");
		$this->getServer()->getLogger()->info(TextFormat::RED . "Copyrights JDNetwork, all rights reserved");
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
 				case "BatPet":
 				break;
 				case "BlazePet":
 				break;
 				case "CowPet":
 				break;
 				case "EndermanPet":
 				break;
 				default:
 					$pets = array("OcelotPet", "PigPet", "WolfPet",  "RabbitPet", "ChickenPet", "BatPet", "BlazePet", "CowPet", "EndermanPet");
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
	}
	
	public function removeEntities() {
   $i = 0;
   foreach($this->getServer()->getLevels() as $level) {
     foreach($level->getEntities() as $entity) {
       if(!$this->isEntityExempted($entity) && !($entity instanceof Creature)) {
         $entity->close();
         $i++;
       }
     }
   }
   return $i;
	}
	
	public function getEntityCount() {
   $ret = [0, 0, 0];
   foreach($this->getServer()->getLevels() as $level) {
     foreach($level->getEntities() as $entity) {
       if($entity instanceof Human) {
         $ret[0]++;
       } else if($entity instanceof Creature) {
         $ret[1]++;
       } else {
         $ret[2]++;
       }
     }
   }
   return $ret;
	}
	public function removeMobs() {
   $i = 0;
   foreach($this->getServer()->getLevels() as $level) {
     foreach($level->getEntities() as $entity) {
       if(!$this->isEntityExempted($entity) && $entity instanceof Creature && !($entity instanceof Human)) {
         $entity->close();
         $i++;
       }
     }
   }
   return $i;
 	}
 
	public function exemptEntity(Entity $entity) {
		$this->exemptedEntities[$entity->getID()] = $entity;
  }
  
  public function isEntityExempted(Entity $entity) {
    return isset($this->exemptedEntities[$entity->getID()]);
	}
  
 	public function onDisable() {
	}
}