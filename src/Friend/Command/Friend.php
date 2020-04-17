<?php

namespace Friend\Command;

use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use Friend\Main;
use pocketmine\utils\Config;
use Friend\jojoe77777\FormAPI\{SimpleForm, ModalForm, CustomForm};
use pocketmine\Player;
class Friend extends PluginCommand{

	public function __construct(Main $plugin){
		$this->p = $plugin;
		parent::__construct("arkadas", $plugin);
		$this->setDescription("Arkadaş Menüsü");
	}

	public function execute(CommandSender $g, string $label, array $args){
		$this->kisic = new Config($this->p->getDataFolder()."Oyuncular/".$g->getName().".yml", Config::YAML);
		$this->arkadasMenu($g);
	}

	public function arkadasMenu(Player $g){
		$form = new SimpleForm(function(Player $g, $args){
			if($args === null){
				return true;
			}
			switch ($args) {
				case 0:
				$g->sendMessage("§cÇıkış yaptın!");
				break;
				case 1:
				$this->arkadasEkle($g);
				break;
				case 2:
				$this->arkadasCikar($g);
				break;
				case 3:
				$this->arkadasIstekleri($g);
				break;
				case 4:
				$this->arkadasIsinlan($g);
				break;
				case 5:
				foreach($this->arkadaslari($g) as $a){
					$g->sendMessage("§a".$a);
				}
				break;
			}
		});
		$form->setTitle("Arkadaş Menüsü");
		$form->addButton("§cÇıkış");
		$form->addButton("Arkadaş Ekle");
		$form->addButton("Arkadaş Çıkar");
		$form->addButton("Arkadaşlık İsteklerin");
		$form->addButton("Arkadaşına Işınlan");
		$form->addButton("Arkadaş Listen");
		$form->sendToPlayer($g);
	}

	public function arkadasEkle(Player $g){
		$form = new CustomForm(function(Player $g, $args){
			if($args === null){
				$this->arkadasMenu($g);
				return true;
			}
			$oy = $this->list[$args[0]];
			$o = $this->p->getServer()->getPlayer($oy);
			$g->sendMessage("§2".$o->getName()." §aadlı oyuncuya istek gönderildi");
			$o->sendMessage("§2".$g->getName()." §asana arkadaşlık isteği gönderdi /arkadas menüsünden kabul edebilirsin!");
			$kisio = new Config($this->p->getDataFolder()."Oyuncular/".$o->getName().".yml", Config::YAML);
			$kisi = $kisio->get("istekler");
			$kisi[] = $g->getName();
			$kisio->set("istekler", $kisi);
			$kisio->save();
		});
		$form->setTitle("Arkadaş Ekle");
		$form->addDropdown("§7Arkadaşlık isteği göndermek istediğin kişiyi seç", $this->aktifOyuncular());
		$form->sendToPlayer($g);
	}

	public function arkadasCikar(Player $g){
		$kisic = new Config($this->p->getDataFolder()."Oyuncular/".$g->getName().".yml", Config::YAML);
		$arkadaslar = $kisic->get("arkadaslari");
		$sayi = count($arkadaslar);
		if(!$sayi == 0){
			$form = new CustomForm(function(Player $g, $args){
				if($args === null){
				    $this->arkadasMenu($g);
				    return true;
			    }
			    $o = $this->alist[$args[0]];
			    if(!$this->p->getServer()->getPlayer($o) == null){
			    	$oy = $this->p->getServer()->getPlayer($o);
			    $kisic = new Config($this->p->getDataFolder()."Oyuncular/".$g->getName().".yml", Config::YAML);
		        $arkadaslar = $kisic->get("arkadaslari");
			    
			    $kisio = new Config($this->p->getDataFolder()."Oyuncular/".$oy->getName().".yml", Config::YAML);
			    $arkadaslaro = $kisio->get("arkadaslari");
			    unset($arkadaslar[array_search($oy->getName(), $arkadaslar)]);
			    $kisic->set("arkadaslari", $arkadaslar);
			    $kisic->save();
			    unset($arkadaslaro[array_search($g->getName(), $arkadaslaro)]);
			    $kisio->set("arkadaslari", $arkadaslaro);
			    $kisio->save();
			    $g->sendMessage("§2".$oy->getName()." §aarkadaşlarından çıkartıldı!");
			}else{
				$g->sendMessage("§aOyuncu oyunda değil!");
			}
			});
			$form->setTitle("Arkadaş Çıkar");
			$form->addDropdown("§7Arkadaşlıklıktan çıkarmak istediğin kişiyi seç", $this->arkadaslari($g));
			$form->sendToPlayer($g);
		}else{
			$g->sendMessage("§cHiç arkadaşın yok!");
		}
	}

	public function arkadasIstekleri(Player $g){
		$kisic = new Config($this->p->getDataFolder()."Oyuncular/".$g->getName().".yml", Config::YAML);
		$istekler = $kisic->get("istekler");
		$sayi = count($istekler);
		if(!$sayi == 0){
			$form = new CustomForm(function(Player $g, $args){
				if($args === null){
				    $this->arkadasMenu($g);
					return true;
			    }
			    $oy = $this->ilist[$args[0]];
			    if(!$this->p->getServer()->getPlayer($oy) == null){
			    $o = $this->p->getServer()->getPlayer($oy);
			    $kisic = new Config($this->p->getDataFolder()."Oyuncular/".$g->getName().".yml", Config::YAML);
		        $arkadaslar = $kisic->get("arkadaslari");
		        $istekler = $kisic->get("istekler");
		        unset($istekler[array_search($o->getName(), $istekler)]);
			    $kisic->set("istekler", $istekler);
			    $kisic->save();
			    $arkadaslar[] = $o->getName();
			    $kisic->set("arkadaslari", $arkadaslar);
			    $kisic->save();
			    $g->sendMessage("§2".$o->getName()." §aarkadaşlarına eklendi!");
			    $kisio = new Config($this->p->getDataFolder()."Oyuncular/".$o->getName().".yml", Config::YAML);
			    $arkadaslaro = $kisio->get("arkadaslari");
			    $arkadaslaro[] = $g->getName();
			    $kisio->set("arkadaslari", $arkadaslaro);
			    $kisio->save(); 
			    $o->sendMessage("§2".$g->getName()." §aarkadaşlık isteğini kabul etti!");
			}else{
				$g->sendMessage("§aOyuncu oyunda değil!");
			}
			});
			$form->setTitle("Arkadaşlık İsteklerin");
			$form->addDropdown("§7Arkadaş isteğini kabul etmek istediğin kişiyi seç", $this->istekleri($g));
			$form->sendToPlayer($g);
		}else{
			$g->sendMessage("§cHiç isteğin yok!");
		}
	}

	public function arkadasIsinlan(Player $g){
		$kisic = new Config($this->p->getDataFolder()."Oyuncular/".$g->getName().".yml", Config::YAML);
		$arkadaslar = $kisic->get("arkadaslari");
		$sayi = count($arkadaslar);
		if(!$sayi == 0){
			$form = new CustomForm(function(Player $g, $args){
				if($args === null){
				    $this->arkadasMenu($g);
					return true;
			    }

			    $oy = $this->alist[$args[0]];
			    if(!$this->p->getServer()->getPlayer($oy) == null){
			    	$o = $this->p->getServer()->getPlayer($oy);
			    	$g->teleport($o->getPosition());
			    	$g->sendMessage("§aBaşarıyla ışınlandın");
			    }else{
			    	$g->sendMessage("§aArkadaşın oyunda değil");
			    }
			});
			$form->setTitle("Arkadaşına Işınlan");
			$form->addDropdown("§7Işınlanmak istediğin arkadaşını seç", $this->arkadaslari($g));
			$form->sendToPlayer($g);
		}else{
			$g->sendMessage("§cHiç arkadaşın yok!");
		}
	}
    public function istekleri(Player $g): array{
		$kisic = new Config($this->p->getDataFolder()."Oyuncular/".$g->getName().".yml", Config::YAML);
		$istekler = $kisic->get("istekler");
		$is = [];
		foreach($istekler as $i){
				$is[] = $i;
				$this->ilist = $is;
		}
		return $is;
	}

	public function arkadaslari(Player $g): array{
		$kisic = new Config($this->p->getDataFolder()."Oyuncular/".$g->getName().".yml", Config::YAML);
		$arkadaslar = $kisic->get("arkadaslari");
		$as = [];
		foreach($arkadaslar as $a){
				$as[] = $a;
				$this->alist = $as;
		}
		return $as;
	}

	public function aktifOyuncular(): array{
	$players = [];
	foreach($this->p->getServer()->getOnlinePlayers() as $oplayers){
		$players[] = $oplayers->getName();
		$this->list = $players;
	}
	return $players;
    }
}
