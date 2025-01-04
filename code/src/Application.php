<?php

namespace Geekbrains\Application1;

class Application
{
    private const APP_NAMESPACE = 'Geekbrains\Application1\Controllers\\';

    private string $controllerName;
    private string $methodName;

    public function run()
    {
        // echo "<pre>";
        // print_r($_SERVER);

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
                return "Метод не существует";
            }
        } else {
            return "Класс ". $this->controllerName. " не существует";
        }

        // создаем экземпляр контроллера, если класс существует
        // проверяем метод на существование
        // вызываем метод, если он существует
    }
}
