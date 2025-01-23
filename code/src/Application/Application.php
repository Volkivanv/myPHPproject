<?php

namespace Geekbrains\Application1\Application;

use Geekbrains\Application1\Infrastructure\Config;

use Geekbrains\Application1\Infrastructure\Storage;

use Geekbrains\Application1\Application\Auth;

use Geekbrains\Application1\Domain\Controllers\AbstractController;

final class Application
{
    private const APP_NAMESPACE = 'Geekbrains\Application1\Domain\Controllers\\';

    private string $controllerName;
    private string $methodName;

    public static Config $config;

    public static Storage $storage;

    public static Auth $auth;
    public function __construct()
    {
        Application::$config = new Config();
        Application::$storage = new Storage();
        Application::$auth = new Auth();
    }

    // private static array $configArr;

    // public static function config(){
    //     return Application::$configArr;
    // }

    public function run()
    {
        session_start();
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

                if ($controllerInstance instanceof AbstractController) {
                    if ($this->checkAccessToMethod($controllerInstance, $this->methodName)) {
                        return call_user_func_array(
                            [$controllerInstance, $this->methodName],
                            []
                        );
                    } else {
                        return "Нет доступа к методу";
                    }
                } else {
                    return call_user_func_array(
                        [$controllerInstance, $this->methodName],
                        []
                    );
                }
            } else {
                header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
                header("Location: error-page.html");
                // header("HTTP/1.1 404 Not Found");
                // header("Location: /404.html");
                echo ("функция " . $this->methodName . " не существует");
                die();
                // return "Метод не существует";
            }
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
            header("Location: error-page.html");

            echo ("Класс " . $this->controllerName . " не существует");

            // header("HTTP/1.1 404 Not Found");
            // header("Location: /404.html");
            die();
        }

        // создаем экземпляр контроллера, если класс существует
        // проверяем метод на существование
        // вызываем метод, если он существует
    }

    private function checkAccessToMethod(
        AbstractController $controllerInstance,
        string $methodName
    ): bool {
        $userRoles = $controllerInstance->getUserRoles();
        $rules = $controllerInstance->getActionsPermissions($methodName);
        $isAllowed = false;
        var_dump($rules);
        var_dump(empty($rules));
        if (!empty($rules)) {
            foreach ($rules as $rolePermission) {
                foreach ($userRoles as $role) {
                    if (in_array($role, $rolePermission)) {
                        $isAllowed = true;
                        break;
                    }
                }
            }
        }
        return $isAllowed;
    }
}
