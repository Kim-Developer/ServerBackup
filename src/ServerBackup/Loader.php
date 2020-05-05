<?php

namespace ServerBackup;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\TextFormat;
use pocketmine\utils\Utils;

use ServerBackup\command\ServerBackupCommand;

class Loader extends PluginBase{

    /** @var string */
    public $prefix = TextFormat::LIGHT_PURPLE . '<' . TextFormat::WHITE . '시스템' . TextFormat::LIGHT_PURPLE . '>' . TextFormat::WHITE . ' ';

    public $os_list = [
        'win' => 'Windows',
        'linux' => 'Linux',
        'mac' => 'Mac',
        'android' => 'Android',
        'ios' => 'IOS'
    ];

    public function onLoad(): void{

        $this->getLogger()->notice('Github: ' . TextFormat::YELLOW . 'https://github.com/Kim-Developer/ServerBackup');
        $this->getLogger()->notice('License: ' . TextFormat::YELLOW . 'https://github.com/Kim-Developer/ServerBackup/blob/master/LICENSE');
        $this->getLogger()->notice('Manual: ' . TextFormat::YELLOW . 'https://github.com/Kim-Developer/ServerBackup/blob/master/README.md');
        $this->getLogger()->notice('Author: ' . TextFormat::YELLOW . 'bl3an_dev / For PocketMine-MP 3.0.0, 4.0.0');

    }

    public function onEnable(): void{
        
        $os = Utils::getOS();
        
        if (isset($this->os_list[$os])){

            if ($this->os_list[$os] === 'Windows' or $this->os_list[$os] === 'Linux'){

                $this->getLogger()->notice('이 플러그인은 윈도우와 리눅스 계열 운영체제만 지원하고 있습니다.');
                $this->getLogger()->notice($this->os_list[$os] . ' 운영체제를 사용중 입니다.');
                $this->getLogger()->notice('플러그인이 ' . TextFormat::GREEN . '활성화' . TextFormat::WHITE . ' 되었습니다.');
    
            }else{

                $this->getLogger()->notice('이 플러그인은 윈도우와 리눅스 계열 운영체제만 지원하고 있습니다.');
                $this->getLogger()->notice($this->os_list[$os] . ' 운영체제를 사용중 입니다.');
                $this->getLogger()->notice('플러그인이 ' . TextFormat::RED . '비활성화' . TextFormat::WHITE . ' 되었습니다.');
    
                $this->getServer()->getPluginManager()->disablePlugin($this);

            }

        }else{

            $this->getLogger()->notice('이 플러그인은 윈도우와 리눅스 계열 운영체제만 지원하고 있습니다.');
            $this->getLogger()->notice($os . ' 운영체제를 사용중 입니다.');
            $this->getLogger()->notice('플러그인이 ' . TextFormat::RED . '비활성화' . TextFormat::WHITE . ' 되었습니다.');
    
            $this->getServer()->getPluginManager()->disablePlugin($this);

        }

        $this->getServer()->getCommandMap()->register('SB', new ServerBackupCommand($this));

    }

}
