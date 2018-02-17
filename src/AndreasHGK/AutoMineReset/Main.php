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
	public $target = mktime(0,0,$timerseconds > $this->getConfig()->get('reset-time'))
	public $current_time = time();
	public $difference = 0;
	
	public function onLoad(){
		$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." Loading...");
		if(is_int($timerseconds > $this->getConfig()->get('reset-time')) == false){
			if(is_bool($timerseconds > $this->getConfig()->get('sleep-when-empty')) == false){
				$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::YELLOW." Config is setup incorrectly!");
			}
		}
	}
	
	public function onEnable(){
		$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." Enabled!");
		$current_time = time();
		$difference = $current_time + $target;
	}
	
	public function update(){
		
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		if(strtolower($cmd->getName()) == "mr"){
			if($sender->hasPermission("minereset.command.resetall")){
				         $success = 0;
            foreach ($this->getApi()->getMineManager() as $mine) {
                if ($mine instanceof Mine) {
                    if ($mine->reset()) { // Only reset if valid
                        $success++;
                        $this->getApi()->getResetProgressManager()->addObserver($mine->getName(), $sender);
                    }
                }
            }
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
				$difference = $current_time + $target;
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
						$difference = $current_time + $target;
						$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." The timer has been auto-enabled!");
					}
			}
		}
	
	public function onDisable(){
		$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." Disabled!");
	}
	public function timercheck(){
		while($current_time > $difference){
				$success = 0;
            foreach ($this->getApi()->getMineManager() as $mine) {
                if ($mine instanceof Mine) {
                    if ($mine->reset()) { // Only reset if valid
                        $success++;
                        $this->getApi()->getResetProgressManager()->addObserver($mine->getName(), $sender);
                    }
                }
            }
            $count = count($this->getApi()->getMineManager());
            $sender->sendMessage("Queued reset for {$success}/{$count} mines.");
			
			$difference = $current_time + $target;
		}
	}
	
}

?>
