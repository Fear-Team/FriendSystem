<?php

namespace Friend\Listener;

use pocketmine\event\Listener;
use Friend\Main;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;
class EventListener implements Listener{

	public function __construct(Main $plugin){
		$this->p = $plugin;
	}

	public function giris(PlayerJoinEvent $ev){
		$g = $ev->getPlayer();
		if(!file_exists($this->p->getDataFolder()."Oyuncular/".$g->getName().".yml")){
			$kisic = new Config($this->p->getDataFolder()."Oyuncular/".$g->getName().".yml", Config::YAML);
			$kisic->set("arkadaslari", array());
			$kisic->set("istekler", array());
			$kisic->save();
		}else{
			$kisic = new Config($this->p->getDataFolder()."Oyuncular/".$g->getName().".yml", Config::YAML);
			$istekler = $kisic->get("istekler");
			$sayi = count($istekler);
			$g->sendMessage("§2".$sayi." §aTane arkadaşlık isteğin var!");
		}
	}
}