<?php

namespace AndreasHGK\AutoMineReset;

use pcoketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as C;

use AndreasHGK\AutoMineReset\TimedReset\ResetMine;
use falkirks\minereset\Mine;

class Main extends PluginBase{
	
	public $prefix = AutoMineReset
	
	public function onLoad(){
		$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." Loading...");
	}
	
	public function onEnable(){
		$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." Enabled!");
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
	
	public function onDisable(){
		$this->getLogger()->notice(C::BOLD.C::RED."[".$this->prefix."]".C::RESET.C::GREEN." Disabled!");
	}
	
}

?>

?>
