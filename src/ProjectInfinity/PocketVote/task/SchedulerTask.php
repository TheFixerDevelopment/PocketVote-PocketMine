<?php

namespace ProjectInfinity\PocketVote\task;

use pocketmine\scheduler\Task;
use ProjectInfinity\PocketVote\PocketVote;

class SchedulerTask extends Task {

    private $plugin;
    private $version;

    public function __construct(PocketVote $plugin) {
        $this->plugin = $plugin;
        $this->version = $plugin->getDescription()->getVersion();
    }

    public function onRun($currentTick) {
        $this->plugin->getLogger()->debug('Checking for outstanding votes.');
        $this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new VoteCheckTask($this->plugin->identity, $this->plugin->secret, $this->version));
    }
}