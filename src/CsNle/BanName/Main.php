<?php


namespace CsNle\BanName;


use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\level\Level;
use pocketmine\Player;

class Main extends PluginBase implements Listener
{

//On Enable + Config Create.
    public function onEnable() {
	    $this->getServer()->getPluginManager()->registerEvents($this, $this);
	    $this->getLogger()->info(TextFormat::DARK_GREEN . "BanName Loading...");
            $this->path = $this->getDataFolder();
		@mkdir($this->path);
		$this->cfg = new Config($this->path."config.yml", Config::YAML,array());
		
		if(!$this->cfg->exists("Names"))
		{
			$this->cfg->set("Names",array("Steve","Fuzhu","Server"));
			$this->cfg->save();
			
             $this->getLogger()->info(" [BanName] Names set to default.");
		}
		if(!$this->cfg->exists("Reason"))
		{
			$this->cfg->set("Reason","Please change your name before login!");
			$this->cfg->save();
			
             $this->getLogger()->info(" [BanName] Reason set to default.");
		}
            $this->saveDefaultConfig();
            $this->reloadConfig();
            $cfg = $this->getConfig();
            $bnames = $cfg->get("Names");
            $reason = $cfg->get("Reason");
			$count = count($bnames);
             $this->getLogger()->info(" [BanName] Enable successfully.");
             $this->getLogger()->info(" [BanName] Load ".$count." names.");
        
	}

	//MAIN
	
public function onPlayerJoin(PlayerJoinEvent $event) {
            $cfg = $this->getConfig();
            $bnames = $cfg->get("Names");
            $reason = $cfg->get("Reason");
			$p = $event->getPlayer();
			$pn = $p->getName();
			if(in_array($pn,$bnames)){
				$p->kick($reason);
				msgOP("[BanName] ".$pn." have been kick by BanName Rules.");
				msgOP("[BanName] Reason:".$reason.".");
			}
}

public function onCommand(CommandSender $sender, Command $cmd, $label, array $arg){
	if($cmd=="bn" OR $cmd=="banname"){
		if(!isOp($sender)){
			$sender->sendMessage("[BanName] Sorry you are not adminer.");
			return false;
		} else {
            $cfg = $this->getConfig();
            $bnames = $cfg->get("Names");
            $reason = $cfg->get("Reason");
			if($arg[0]=="add"){
				if(isset($arg[1])){
				array_push($bnames,$arg[1]);
				$this->cfg->set("Names",$bnames);
				$sender->sendMessage("[BanName] ".$arg[1]." have been add to rules.");
				$sender->sendMessage("[BanName] Rules:");
				foreach($bnames as $bn){
				$sender->sendMessage("[BanName] [BAN] ".$bn);
				}
				$sender->sendMessage("[BanName] :)");
            $this->saveDefaultConfig();
            $this->reloadConfig();
			$this->getLogger()->info(" [BanName] ".$sender." changed rules.");
				return true;
				} else { return false; }
			} elseif($arg[0]=="del"){
				$sender->sendMessage("[BanName] I'm sorry that this feature are not avaiable.");
				$sender->sendMessage("[BanName] You can modify the config file and then run the reload cmd.");
			} elseif($arg[0]=="set"){
				if(isset($arg[1])){
				$this->cfg->set("Reason",$arg[1]);
				$sender->sendMessage("[BanName] Changed.");
            $this->saveDefaultConfig();
            $this->reloadConfig();
			$this->getLogger()->info(" [BanName] ".$sender." changed reason.");
				} else { return false; }
			} elseif($arg[0]=="ls"){
				$sender->sendMessage("[BanName] Rules:");
				foreach($bnames as $bn){
				$sender->sendMessage("[BanName] [BAN] ".$bn);
				}
				$sender->sendMessage("[BanName] :)");
			} elseif($arg[0]=="ls"){
				if(!isset($arg[1])){
				$sender->sendMessage("[BanName] HELP PAGE ======");
				$sender->sendMessage("[BanName] Root command:/banname");
				$sender->sendMessage("[BanName] add ID     add a player.");
				$sender->sendMessage("[BanName] ls         list the rule.");
				$sender->sendMessage("[BanName] rl         reload config.");
				$sender->sendMessage("[BanName] set TEXT   set reason.");
				} elseif($arg[1]=="ls"){
				$sender->sendMessage("[BanName] HELP PAGE ======");
				$sender->sendMessage("[BanName] Root command:/banname");
				$sender->sendMessage("[BanName] add ID     add a player.");
				} elseif($arg[1]=="set"){
				$sender->sendMessage("[BanName] HELP PAGE ======");
				$sender->sendMessage("[BanName] Root command:/banname");
				$sender->sendMessage("[BanName] set TEXT   set reason.");
				} elseif($arg[1]=="add"){
				$sender->sendMessage("[BanName] HELP PAGE ======");
				$sender->sendMessage("[BanName] Root command:/banname");
				$sender->sendMessage("[BanName] ls         list the rule.");
				} elseif($arg[1]=="rl"){
				$sender->sendMessage("[BanName] HELP PAGE ======");
				$sender->sendMessage("[BanName] Root command:/banname");
				$sender->sendMessage("[BanName] rl         reload config.");
				} else {
				$sender->sendMessage("[BanName] Unknow cmd.");
				$sender->sendMessage("[BanName] HELP PAGE ======");
				$sender->sendMessage("[BanName] Root command:/banname");
				$sender->sendMessage("[BanName] add ID     add a player.");
				$sender->sendMessage("[BanName] ls       list the rule.");
				$sender->sendMessage("[BanName] rl       reload config.");
				}
			} elseif($arg[0]=="rl"){
			$this->getLogger()->info(" [BanName] ".$sender." reloading rules.");
				$sender->sendMessage("[BanName] Reloading...");
            $this->reloadConfig();
            $cfg = $this->getConfig();
            $bnames = $cfg->get("Names");
            $reason = $cfg->get("Reason");
			$count = count($bnames);
             $this->getLogger()->info(" [BanName] Enable successfully.");
             $this->getLogger()->info(" [BanName] Load ".$count." names.");
				$sender->sendMessage("[BanName] Load completely.");
			}
		}
	}
}

public function onDisable(){
        $this->saveDefaultConfig();
	    $this->getLogger()->info(" [BanName] Config saved.");
}

//APIs

public function getBNlist(){
            $cfg = $this->getConfig();
            $bnames = $cfg->get("Names");
			return $bnames;
}

public function getBNReason(){
            $cfg = $this->getConfig();
            $reason = $cfg->get("Reason");
			return $reason;
}

public function is_BanName($name) {
    $cfg = $this->getConfig();
    $bnames = $cfg->get("Names");
	if($name instanceof Player) {
		$pn = $name->getName();
	if(in_array($pn,$bnames)){
		return true
	}
	}
	return false;
}

//PLUGINHELPER

public function msgOP($msg) {
	if(!isset($msg)) { return false; }
	$allp = Server::getOnlinePlayers();
	foreach($allp as $p){
		if(Server::isOp($p)){
			$p->sendMessage($msg);
		}
	}
}

}