<?php

namespace ProjectInfinity\PocketVote\cmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use ProjectInfinity\PocketVote\PocketVote;
use ProjectInfinity\PocketVote\task\TopVoterTask;

class VoteCommand extends Command implements PluginIdentifiableCommand {

    private $plugin;

    public function __construct(PocketVote $plugin) {
        parent::__construct('vote', 'PocketVote vote command', '/vote', ['v']);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if(!$sender->hasPermission('pocketvote.vote')) {
            $sender->sendMessage(TextFormat::RED.'You do not have permission use /vote.');
            return true;
        }
        if(isset($args[0]) && strtoupper($args[0]) === 'TOP') {
            $this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new TopVoterTask($this->plugin->identity, $sender->getName()));
            return true;
        }
        $link = $this->plugin->getVoteManager()->getVoteLink();
        if($link === null) {
            if($sender->hasPermission('pocketvote.admin')) {
                $sender->sendMessage(TextFormat::YELLOW.'You can add a link by typing /guadd');
                $sender->sendMessage(TextFormat::YELLOW.'See /guru for help!');
            } else {
                $sender->sendMessage(TextFormat::YELLOW.'The server operator has not added any voting sites.');
            }
            return true;
        }
        if($sender->hasPermission('pocketvote.admin')) $sender->sendMessage(TextFormat::YELLOW.'Use /guru to manage this link.');
        $sender->sendMessage(TextFormat::AQUA.'You can vote at '.$link);
        return true;
    }

    public function getPlugin(): Plugin {
        return $this->plugin;
    }
}
