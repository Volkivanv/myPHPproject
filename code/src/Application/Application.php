<?php

namespace Geekbrains\Application1\Application;

use Geekbrains\Application1\Infrastructure\Config;

use Geekbrains\Application1\Infrastructure\Storage;

final class Application
{
    private const APP_NAMESPACE = 'Geekbrains\Application1\Domain\Controllers\\';

    private string $controllerName;
    private string $methodName;

    public static Config $config;

    public static Storage $storage;
    public function __construct()
    {
        Application::$config = new Config();
        Application::$storage = new Storage();
    }

    // private static array $configArr;

    // public static function config(){
    //     return Application::$configArr;
    // }

    public function run()
    {
        // echo "<pre>";
        // print_r($_SERVER);
        // Application::$configArr = parse_ini_file('./src/config/config.ini',true);

        // разбиваем адрес по символу слеша
        $routeArray = explode('/', $_SERVER['REQUEST_URI']);

        // определяем имя контроллера
        if (isset($routeArray[1]) && $routeArray[1] != '') {
            $controllerName = $routeArray[1];
        } else {
            $controllerName = "page";
        }
        $this->controllerName = Application::APP_NAMESPACE . ucfirst($controllerName) . "Controller";

        // проверяем контроллер на существование
        if (class_exists($this->controllerName)) {
            // пытаемся вызвать метод
            if (isset($routeArray[2]) && $routeArray[2] != '') {
                $methodName = $routeArray[2];
            } else {
                $methodName = "index";
            }
            $this->methodName = "action" . ucfirst($methodName);
            if (method_exists($this->controllerName, $this->methodName)) {
                $controllerInstance = new $this->controllerName();
                return call_user_func_array(
                    [$controllerInstance, $this->methodName],
                    []
                );
            } else {
                header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
                header("Location: error-page.html");
                die();
                // return "Метод не существует";
            }
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
            header("Location: error-page.html");
            die();
            // return "Класс ". $this->controllerName. " не существует";
        }

        // создаем экземпляр контроллера, если класс существует
        // проверяем метод на существование
        // вызываем метод, если он существует
    }

    
}
