<?php

namespace ServerBackup\task;

use pocketmine\scheduler\AsyncTask;

use pocketmine\utils\TextFormat;
use pocketmine\utils\Utils;

use pocketmine\Server;

class ServerBackupAsync_4 extends AsyncTask{

    /** @var string */
    public $path;

    /** @var string */
    public $backupPath;

    /** @var string */
    public $serverOS;

    /** @var string */
    public $date;

    public function __construct(string $dataPath){
        $this->path = $dataPath;
        $this->serverOS = Utils::getOS();
        $this->date = date('Y-m-d-H-i-s');
    }

    public function onRun(): void{
        $this->OSCheck();
    }

    public function onCompletion(): void{
        $plugin = Server::getInstance()->getPluginManager()->getPlugin('ServerBackup');
        Server::getInstance()->broadcastMessage($plugin->prefix . '정상적으로 서버 백업이 완료 되었습니다..');
        Server::getInstance()->broadcastMessage($plugin->prefix . '백업 파일이 저장된 경로: ' . TextFormat::YELLOW . $this->backupPath);
        $plugin->backupMode = 'off';
    }

    public function OSCheck(): void{
        if ($this->serverOS === 'win'){
            $this->WindowsBackup();
        }else if ($this->serverOS === 'linux'){
            $this->LinuxBackup();
        }else{
            // nothing..
        }
    }

    public function LinuxBackup(): void{
        $path = str_replace(explode('/', $this->path)[mb_substr_count($this->path, '/', 'utf-8') - 1] . '/', '', $this->path);
        if (!is_dir($path . 'backup/')){
            @mkdir($path . 'backup/');
        }
        $zip = new \ZipArchive;
        $zip->open($path . 'backup/' . $this->date . '.zip', \ZipArchive::CREATE);
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->path),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $objects){
            if (!$objects->isDir()){
                $zip->addFile($objects, substr($objects, strlen($this->path)));
            }
        }
        $zip->close();
        $this->backupPath = $path . 'backup/' . $this->date . '.zip';
    }

    public function WindowsBackup(): void{
        $path = str_replace(explode('\\', $this->path)[mb_substr_count($this->path, '\\', 'utf-8') - 1] . '\\', '', $this->path);
        if (!is_dir($path . 'backup\\')){
            @mkdir($path . 'backup\\');
        }
        $zip = new \ZipArchive;
        $zip->open($path . 'backup\\' . $this->date . '.zip', \ZipArchive::CREATE);
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->path),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $objects){
            if (!$objects->isDir()){
                $zip->addFile($objects, substr($objects, strlen($this->path)));
            }
        }
        $zip->close();
        $this->backupPath = $path . 'backup\\' . $this->date . '.zip';
    }

}
