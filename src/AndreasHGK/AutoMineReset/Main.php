<?php

namespace AndreasHGK\AutoMineReset;

use pcoketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as C;

use AndreasHGK\AutoMineReset\TimedReset\ResetMine;
use falkirks\minereset\Mine;
use AndreasHGK\AutoMineReset\Timer;

class Main extends PluginBase{
	
	public $prefix = AutoMineReset;
	public $paused = false;
	public $autopaused = false;
	public $interval = 600;
	public $seconds = 0;
	
	public function onLoad(){
		$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." Loading...");
		if(is_int($timerseconds > $this->getConfig()->get('reset-time')) == false){
			if(is_bool($timerseconds > $this->getConfig()->get('sleep-when-empty')) == false){
				$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::YELLOW." Config is setup incorrectly!");
			}
		}
		$interval = $this->getConfig()->get('reset-time');
	}
	
	public function onEnable(){
		$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." Enabled!");
		$current_time = time();
	}
	
	setInterval(public function update(){
		autoresettask;
		autostop;
	},1000);
	
	setInterval(public function betterTimer(){
		if(pause == false) && {
			$seconds++;
		}
		if($seconds > $interval){
			$seconds = 0;
		}
	},1000)
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		if(strtolower($cmd->getName()) == "mr"){
			if($sender->hasPermission("minereset.command.resetall")){
				resetAll;
            $count = count($this->getApi()->getMineManager());
            $sender->sendMessage("Queued reset for {$success}/{$count} mines.");
			}
			else{
            $sender->sendMessage(TextFormat::RED . "You do not have permission to run this command." . TextFormat::RESET);
            }
		}
	
	}
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		if(strtolower($cmd->getName()) == "autoreset"){
			if($sender->hasPermission("amr.autoreset")){
				if($paused === true){
				$paused = false;
				$sender->sendMessage(C::BOLD.C::GREEN."Autoreset enabled!");
				$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." The timer has been enabled by ".$sender."!");
				}else{
				$paused = true;
				$sender->sendMessage(C::BOLD.C::GREEN."Autoreset disabled!");
				$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." The timer has been disabled by ".$sender."!");
				$autopaused = false;
				}
			} else {
            $sender->sendMessage(TextFormat::RED . "You do not have permission to run this command." . TextFormat::RESET);
		}
	}
	public function autostop(){
			if($this->getConfig()->get('sleep-when-empty') == true){
				if(count($this->getServer()->getOnlinePlayers()) < 0){
					if($paused == false){
						$paused = true;
						$autopaused = true;
						$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." The timer has been auto-disabled!");
					}
					}elseif($autopaused == true) {
						$paused = false;
						$autopaused = false;
						$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." The timer has been auto-enabled!");
					}
			}
		}
	
	public function resetAll(){
						$success = 0;
            foreach ($this->getApi()->getMineManager() as $mine) {
                if ($mine instanceof Mine) {
                    if ($mine->reset()) { // Only reset if valid
                        $success++;
                        $this->getApi()->getResetProgressManager()->addObserver($mine->getName(), $sender);
                    }
                }
            }
			$this->getServer()-broadcastMessage(C::BOLD.C::RED."All mines have been reset!")
	}
	
	public function onDisable(){
		$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." Disabled!");
	}
	public function autoresettask(){
		while($seconds >= $interval){
			resetAll;
		}
	}
	
}
}

?>
