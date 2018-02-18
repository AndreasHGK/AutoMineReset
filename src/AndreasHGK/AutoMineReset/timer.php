<?php

namespace AndreasHGK\AutoMineReset;

use pocketmine\scheduler\PluginTask;
use AndreasHGK\AutoMineReset\Main;

class YourTask extends PluginTask {

    public $plugin;
    public $seconds = 0;

      public function __construct(Main $plugin, Player $player, $time) {
          parent::__construct($plugin);
          $this->plugin = $plugin;
      }

      public function getPlugin() {
          return $this->plugin;
      }

      public function onRun($tick) {
          // Sends a message to the console with how many seconds the task has been running for
          $this->getPlugin()->getLogger()->info("YourTask has run for " . $this->seconds . "!");
          // Checks if $this->seconds has the same value of 10
          if($this->seconds === 10) {
              // Tells the console that the task is being stopped and at how many seconds
              $this->getPlugin()->getLogger()->info("YourTask has run for " . $this->seconds . " and is now stopping...");
              // Calls a function from your Main that removes the task and stops it from running
              $this->getPlugin()->removeTask($this->getTaskId());
          }
          // Adds 1 to $this->seconds
          $this->seconds++;
      }
}
