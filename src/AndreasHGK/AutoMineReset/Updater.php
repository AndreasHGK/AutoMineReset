<?php
namespace AndreasHGK\AutoMineReset;
use pocketmine\scheduler\Task;
use AndreasHGK\AutoMineReset\Main as M;

class Updater extends Task {
	
	public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
	
    public function onRun($tick) {
        $this->plugin->update();
    }
}
