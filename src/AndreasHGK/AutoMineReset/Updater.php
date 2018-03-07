<?php
namespace AndreasHGK\AutoMineReset;
use pocketmine\scheduler\PluginTask;
use AndreasHGK\AutoMineReset\Main as M;

class Updater extends PluginTask {
	
	public function __construct(Main $plugin){
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }
	
    public function onRun($tick) {
        $this->plugin->update();
    }
}