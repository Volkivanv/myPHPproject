<?php 

namespace Geekbrains\Application1\Domain\Controllers;

class Controller
{
    public function __construct(){
        if(!isset($_SESSION['counter'])){
            $_SESSION['counter'] = 0;
        } 

        $_SESSION['counter']++;
    }
}