<?php
namespace AndreasHGK\AutoMineReset;
use pocketmine\command\CommandExecutor;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;
use AndreasHGK\AutoMineReset\TimedReset\ResetMine;
use falkirks\minereset\Mine;
use AndreasHGK\AutoMineReset\Timer;
class Main extends PluginBase{
	
	public function onLoad(){
		$this->getLogger()->notice(C::GREEN." Loading...");
		$paused = false;
		$autopaused = false;
		$interval = 600;
		$sec = 0;
		$this->saveDefaultConfig();
        	$this->reloadConfig();
		if(is_int($this->getConfig()->get('reset-time')) == false){
			if(is_bool($timerseconds > $this->getConfig()->get('sleep-when-empty')) == false){
				$this->getLogger()->notice(C::BOLD.C::RED."[FATAL]".C::RESET.C::YELLOW." Config is setup incorrectly!");
			}
		}
		$interval = $this->getConfig()->get('reset-time');
	global $paused;
	global $autopaused;
	global $interval;
	global $sec;
	}
	
	public function onEnable(){
		if(!isset($sec)){
    	$sec = 0;
		}
		$this->getLogger()->notice(C::GREEN." Enabled!");
		$current_time = time();	
		
		$this->setInterval($this->update($this->getConfig()->get('reset-time')),1000);
	}
	
	public function setInterval($f, $milliseconds)
		{
			$seconds=(int)$milliseconds/1000;
			while(true)
			{
				$f();
				sleep($seconds);
			}
		}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
		if(strtolower($cmd->getName()) == "mr"){
			if($sender->hasPermission("minereset.command.resetall")){
				$this->resetAll();
				$count = count($this->getApi()->getMineManager());
				$sender->sendMessage("Queued reset for {$success}/{$count} mines.");
			}
			else{
            $sender->sendMessage(TextFormat::RED . "You do not have permission to run this command." . TextFormat::RESET);
            }
		} elseif(strtolower($cmd->getName()) == "autoreset"){
			if($sender->hasPermission("amr.autoreset")){
				if($paused === true){
				$paused = false;
				$sender->sendMessage(C::BOLD.C::GREEN."Autoreset enabled!");
				$this->getLogger()->notice(C::GREEN." The timer has been enabled by ".$sender."!");
				}else{
				$paused = true;
				$sender->sendMessage(C::BOLD.C::GREEN."Autoreset disabled!");
				$this->getLogger()->notice(C::GREEN." The timer has been disabled by ".$sender."!");
				$autopaused = false;
				}
			} else {
            $sender->sendMessage(TextFormat::RED . "You do not have permission to run this command." . TextFormat::RESET);
		}
	}
	
	}
	public function autostop(){
			if($this->getConfig()->get('sleep-when-empty') == true){
				if(count($this->getServer()->getOnlinePlayers()) < 0){
					if($paused == false){
						$paused = true;
						$autopaused = true;
						$this->getLogger()->notice(C::GREEN." The timer has been auto-disabled!");
					}
					} else {
						$paused = false;
						$autopaused = false;
						$this->getLogger()->notice(C::GREEN." The timer has been auto-enabled!");
					}
			}
		}
	public function resetAll(){
		$server->dispatchCommand(new ConsoleCommandSender(), 'mine reset-all');
		
		foreach($this->getServer()->getOnlinePlayers() as $p){
			$p->sendMessage(C::BOLD.C::RED."All mines have been reset!");
		}
	}
	
	public function onDisable(){
		$this->getLogger()->notice(C::GREEN." Disabled!");
	}
	
	public function autoresettask($in){
		while($sec >= $in){
			$this->resetAll();
		}
	}
	public function update($interval2) {
		if(!isset($interval2)){
    	$interval = 600;
	}
		$this->autoresettask($interval2);
		$this->autostop();
		$this->bettertimer();
		}
	
	public function betterTimer() {
		if(pause == false){
			$sec++;
		}
		if($sec > $interval){
			$sec = 0;
		}
	}
	
}
