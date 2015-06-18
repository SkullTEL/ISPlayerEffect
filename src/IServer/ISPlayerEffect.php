<?php
namespace IServer;

use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\level\Level;
use pocketmine\utils\TextFormat;
use pocketmine\Plugin\PluginBase; 
use pocketmine\entity\Effect;
use onebone\economyapi\EconomyAPI;

class ISPlayerEffect extends PluginBase implements Listener{
	 public $version = "V[1.2.1]Test0";
	 private static $instance;
    public static function getInstance() {
        return self::$instance;
    }
	public function onEnable(){ 
	     self::$instance = $this;
		$this->path = $this->getDataFolder();
		@mkdir($this->path);
		$this->getLogger()->info(">>   §bISPlayerEffect - Loaded!");
		 $this->getLogger()->info(">>   §bby:SkullTEL   ：" .$this->version);
		
   	    $this->cfg=new Config($this->getDataFolder()."money.yml",Config::YAML,array(
		 "economy-plugin" => "EconomyAPI"
		 ));
		 if(!$this->cfg->exists("DurationPrice"))
 {
 $this->cfg->set("DurationPrice","500");
 $this->cfg->save();
 }
 if(!$this->cfg->exists("APrice"))
 {
 $this->cfg->set("APrice","1000");
 $this->cfg->save();
 }
 $this->DurationPrice = $this->cfg->get("DurationPrice");
 $this->APrice = $this->cfg->get("APrice");
 
		 $this->getServer()->getPluginManager()->registerEvents($this, $this);
		 
				 if($this->cfg->get("economy-plugin") == "EconomyAPI"){
   	if(is_dir($this->getServer()->getPluginPath()."EconomyAPI")){
				$this->getLogger()->info(">>   succeed pairing with EconomyAPI!");
				$this->cfg = true;
			}else{
				$this->getLogger()->info(">>   Didn't find EconomyAPI!");
				$this->cfg = false;
			}  
			}
			
	}
public function onDisable()
    {
    $this->getLogger()->info(">>   §bISPlayerEffect - Uninstalled!");
				}
public function onCommand(CommandSender $sender, Command $command, $label, array $args){
	$sn = $sender->getName();
	$pm = EconomyAPI::getInstance()->myMoney($sn);
		switch($command->getName()){
		 case "pe":
		if($sender instanceof Player){
  if(isset($args[2])){
//Check Error -------------------------------------------------------------------------------
  if($args[0] <= 0){$this->ErrorMessage(5,$sender);break;}
  if($args[1] <= 0){$this->ErrorMessage(8,$sender);break;}
  if($args[2] <= 0){$this->ErrorMessage(4,$sender);break;}
  if($args[0] != 1 or $args[0] != 3 or $args[0] != 2 or $args[0] != 4 or $args[0] != 5 or $args[0] != 8 or $args[0] != 9 or $args[0] != 10 or $args[0] != 11 or $args[0] != 12 or $args[0] != 13 or $args[0] != 14 or $args[0] != 18 or $args[0] != 19 or $args[0] != 20){$this->ErrorMessage(3,$sender);break;}
  if($args[2] != 1 or $args[2] != 2 or $args[2] != 3 or $args[2] != 4 or $args[2] != 5){$this->ErrorMessage(2,$sender);break;}
//Money Cost  -------------------------------------------------------------------------------
  $jg1 = $args[1] * $this->DurationPrice;
  $jg2 = $args[2] * $this->APrice;
  $jg = $jg1 + $jg2;
//Pay Money   -------------------------------------------------------------------------------
  if($pm < $jg){$this->ErrorMessage(1,$sender);break;}
    $effect = Effect::getEffect($args[0]);
		$effect->setDuration(1200 * $args[1])->setAmplifier($args[2]-1); 
   $sender->addEffect($effect);
     EconomyAPI::getInstance()->reduceMoney($sn,$jg);
   foreach ($this->getServer()->getOnlinePlayers() as $op) {
     $op->sendTip(">>   §b$sn bought an effect for $ $ $jg ");
     }}else{$this->ErrorMessage(6,$sender);}}else{$this->ErrorMessage(7,$sender);}
  return true;
     break;
}}

public function ErrorMessage($ErrorID,$Sender){
  switch($ErrorID){
  case 1 :$Sender->sendMessage(">>   §bYou Haven't enough money to pay for this effect!");break;
  case 2 :$Sender->sendMessage(">>   §bAmplifier must be lesser than 5!");break;
  case 3 :$Sender->sendMessage(">>   §bThere's no such effect!");break;
  case 4 :$Sender->sendMessage(">>   §bAmplifier must above 0");break;
  case 5 :$Sender->sendMessage(">>   §bEffectID must above 0");break;
  case 6 :$Sender->sendMessage(">>   §bUsage:/pe EffectID Duration[$500/min] Amplifier[1 is free,and $1000 per level above 1]");break;
  case 7 :$Sender->sendMessage(">>   §bPlz use this command in game!");break;
  case 8 :$Sender->sendMessage(">>   §bDuration can't be lesser than 1");break;
  }
}}