<?php

namespace ServerBackup\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use ServerBackup\task\ServerBackupAsync_3;
use ServerBackup\task\ServerBackupAsync_4;

use ServerBackup\Loader;

class ServerBackupCommand extends Command{

    /** @var Loader */
    public $owner;

    public function __construct(Loader $owner){
        $this->owner = $owner;
        parent::__construct('sb', 'ServerBackupCommand', '/sb');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {

        if (!$sender->isOp()){
            $sender->sendMessage($this->owner->prefix . '명령어를 실행 할 권한이 없습니다');
            return true;
        }

        if (substr($this->owner->getServer()->getApiVersion(), 0, 1) === '4'){ // API 4.0.0
            $this->owner->getServer()->getAsyncPool()->submitTask(new ServerBackupAsync_4($this->owner->getServer()->getDataPath()));
        }else{ // API 3.0.0, 2.0.0, ...
            $this->owner->getServer()->getAsyncPool()->submitTask(new ServerBackupAsync_3($this->owner->getServer()->getDataPath()));
        }
        
        $this->owner->getServer()->broadcastMessage($this->owner->prefix . '서버 백업을 시작 했습니다..');
        return true;

    }

}
