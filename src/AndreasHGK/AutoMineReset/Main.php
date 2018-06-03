<?php
namespace AndreasHGK\AutoMineReset;
use pocketmine\command\CommandExecutor;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\Command;
use pocketmine\scheduler\PluginTask;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;
class Main extends PluginBase{
	
	public $paused = false;
	private $sec = 0;
	public $autopaused = false;
	private $interval = 600;
	
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
		$this->sec = 1;
		$this->interval = $this->getConfig()->get('reset-time');
		$this->milliseconds = 1000;
		$task = new Updater($this);
		$h = $this->getServer()->getScheduler()->scheduleRepeatingTask($task, 20);
	}
	
	public function onEnable(){
		$this->getLogger()->notice(C::GREEN." Enabled!");
		$this->prepLoop();
	}
	
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
		switch(strtolower($cmd->getName())){
			case "mr";
			if($sender->hasPermission("minereset.command.resetall")){
				$this->resetAll();
				return true;
			}
			else{
            $sender->sendMessage(C::RED . "You do not have permission to run this command." . C::RESET);
			return true;
            }
			break;
		case "autoreset":
			if($sender->hasPermission("amr.autoreset")){
				if($this->paused == true){
				$this->paused = false;
				$sender->sendMessage(C::BOLD.C::AQUA."»".C::RESET.C::DARK_AQUA." Automatic reset has been ".C::GREEN."enabled".C::DARK_AQUA."!");
				$this->getLogger()->notice(C::GREEN." The timer has been enabled by ".$sender->getName()."!");
				}else{
				$this->paused = true;
				$sender->sendMessage(C::BOLD.C::AQUA."»".C::RESET.C::DARK_AQUA." Automatic reset has been ".C::RED."disabled".C::DARK_AQUA."!");
				$this->getLogger()->notice(C::GREEN." The timer has been disabled by ".$sender->getName()."!");
				$this->autopaused = false;
				}
			} else {
            $sender->sendMessage(C::RED . "You do not have permission to run this command." . C::RESET);
		}
		return true;
		break;
	}
	}
	
	public function autostop(){
		static $y = false;
			if($this->getConfig()->get('sleep-when-empty') == true){
				if(count($this->getServer()->getOnlinePlayers()) != 0){
					if($this->paused == true){
						if($y == true){
						$this->paused = false;
						$this->autopaused = false;
						$y = false;
						$this->getLogger()->notice(C::GREEN." The timer has been auto-enabled!");
						}
					}
					} else {
						if($y == false) {
						$this->paused = true;
						$y = true;
						$this->autopaused = true;
						$this->getLogger()->notice(C::GREEN." The timer has been auto-disabled!");
						}
					}
			}
		}
	public function resetAll(){
		$command = "mine reset-all";
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),$command);
		
		Server::getInstance()->broadcastMessage(C::BOLD.C::AQUA."»".C::RESET.C::DARK_AQUA." All mines have been reset!");
		}
	
	
	public function onDisable(){
		$this->getLogger()->notice(C::GREEN." Disabled!");
	}
	
	public function update() {
		$this->autostop();
		$this->bettertimer();
		if($this->sec >= $this->interval){
			$this->resetAll();
		}
		}
	
	public function betterTimer() {
		if($this->paused == false){
			$this->sec++;
		}
		if($this->sec > $this->interval){
			$this->sec = 1;
		}
		//$this->getLogger()->notice(C::GREEN."Debug(sec): ".$this->sec);
		//$this->getLogger()->notice(C::GREEN."Debug(interval): ".$this->interval);
	}
	
}