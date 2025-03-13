<?php

require_once('../vendor/autoload.php');

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Render;

try {
    $app = new Application();

    $result = $app->runApp();

    echo $result;
}catch (Exception $e) {
    //echo "При старте приложения произошла ошибка. ". $e->getMessage();

    echo Render::renderExceptionPage($e);
}


