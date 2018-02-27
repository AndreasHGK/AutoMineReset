<?php
namespace AndreasHGK\AutoMineReset;
use pocketmine\command\CommandExecutor;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;
class Main extends PluginBase{
	
	public $paused = false;
	public $sec = 0;
	public $autopaused = false;
	public $interval = 600;
	
	public function onLoad(){
		$this->getLogger()->notice(C::GREEN." Loading...");
		$this->saveDefaultConfig();
        	$this->reloadConfig();
		if(is_int($this->getConfig()->get('reset-time')) == false){
			if(is_bool($timerseconds > $this->getConfig()->get('sleep-when-empty')) == false){
				$this->getLogger()->notice(C::BOLD.C::RED."[FATAL]".C::RESET.C::YELLOW." Config is setup incorrectly!");
			}
		}

	}
	
	public function prepLoop(){
		global $interval;
		global $sec;
		$sec = 1;
		$interval = $this->getConfig()->get('reset-time');
		$milliseconds = 1000;
		$seconds=(int)$milliseconds/1000;
			while(true)
			{
				$this->update();
				sleep($seconds);
			}
	}
	
	public function onEnable(){
		$this->getLogger()->notice(C::GREEN." Enabled!");
		$this->prepLoop();
	}
	
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
		global $autopaused;
		global $paused;
		switch($command->getName()){
			case mr:
			if($sender->hasPermission("minereset.command.resetall")){
				$this->resetAll();
				$count = count($this->getApi()->getMineManager());
				$sender->sendMessage("Queued reset for {$success}/{$count} mines.");
			}
			else{
            $sender->sendMessage(TextFormat::RED . "You do not have permission to run this command." . TextFormat::RESET);
            }
		
		case autoreset:
			if($sender->hasPermission("amr.autoreset")){
				if($paused == true){
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
	}}
	
	public function autostop(){
		static $y = false;
		global $paused;
		global $autopaused;
			if($this->getConfig()->get('sleep-when-empty') == true){
				if(count($this->getServer()->getOnlinePlayers()) < 0){
					if($paused == false){
						if($y == true){
						$paused = false;
						$autopaused = false;
						$y = false;
						$this->getLogger()->notice(C::GREEN." The timer has been auto-enabled!");
						}
					}
					} else {
						if($y == false) {
						$paused = true;
						$y = true;
						$autopaused = true;
						$this->getLogger()->notice(C::GREEN." The timer has been auto-disabled!");
						}
					}
			}
		}
	public function resetAll(){
		$cmd = "mine reset-all";
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),$cmd);
		
		Server::getInstance()->broadcastMessage(C::BOLD.C::DARK_RED."[AutoMineReset] ".C::RESET.C::YELLOW." All mines have been reset!");
		}
	
	
	public function onDisable(){
		$this->getLogger()->notice(C::GREEN." Disabled!");
	}
	
	public function update() {
		$this->autostop();
		$this->bettertimer();
		global $sec;
		global $interval;
		if($sec >= $interval){
			$this->resetAll();
		}
		}
	
	public function betterTimer() {
		global $interval;
		global $paused;
		global $sec;
		if($paused == false){
			$sec++;
		}
		if($sec > $interval){
			$sec = 1;
		}
		//$this->getLogger()->notice(C::GREEN." Debug says(sec): ".$sec);
		//$this->getLogger()->notice(C::GREEN." Debug says(int): ".$interval);
	}
	
}