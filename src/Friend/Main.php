<?php

namespace Friend;

use pocketmine\plugin\PluginBase;
use Friend\Listener\EventListener;
use Friend\Command\Friend;

class Main extends PluginBase{

	public function onEnable(){
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder()."Oyuncular/");
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getServer()->getCommandMap()->register("arkadas", new Friend($this));
	}
}