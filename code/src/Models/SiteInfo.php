<?php 

namespace Geekbrains\Application1\Models;

class SiteInfo
{
    private array $info;

    public function __construct()
    {
        $this->info['webserver'] = $_SERVER['SERVER_SOFTWARE'];
        $this->info['interpretator'] = $_SERVER['PHP_VERSION'];
        $this->info['browser'] = $_SERVER['HTTP_USER_AGENT'];
    }

    public function getInfo(){
        return $this->info;
    }

    public function getName(){
        return $_SERVER['SERVER_NAME'];
    }

}