<?php

namespace Geekbrains\Application1\Application;

use Geekbrains\Application1\Infrastructure\Config;

use Geekbrains\Application1\Infrastructure\Storage;

use Geekbrains\Application1\Application\Auth;

use Geekbrains\Application1\Domain\Controllers\AbstractController;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Level;
use Monolog\Handler\FirePHPHandler;


final class Application
{
    private const APP_NAMESPACE = 'Geekbrains\Application1\Domain\Controllers\\';

    private string $controllerName;
    private string $methodName;

    public static Config $config;

    public static Storage $storage;

    public static Auth $auth;

    public static Logger $logger;

    public function __construct()
    {
        Application::$config = new Config();
        Application::$storage = new Storage();
        Application::$auth = new Auth();

        Application::$logger = new Logger('application_logger');
        Application::$logger->pushHandler(
            new StreamHandler(
                $_SERVER['DOCUMENT_ROOT'] . "/log/" . Application::$config->get()['log']['LOGS_FILE'] . "-" . date("Y-m-d") . ".log",
                Level::Debug
            )
        );
        Application::$logger->pushHandler(new FirePHPHandler());
    }

    // private static array $configArr;

    // public static function config(){
    //     return Application::$configArr;
    // }

    public function runApp()
    {
        $memory_start = memory_get_usage();

        $result = $this->run();
        //добавить if config
        if (Application::$config->get()['log']['DB_MEMORY_LOG']) {
            $memory_end = memory_get_usage();


            $this->saveMemoryLog($memory_end - $memory_start);
            // echo "<h4>Потреблено " . ($memory_end - $memory_start) / 1024 / 1024 . " МБ памяти</h4>";

        }

        return $result;
    }

    private function saveMemoryLog(int $memory)
    {
        $logSql = "INSERT INTO memory_log(`user_agent`, `log_datetime`, `url`, `memory_volume`) VALUES (:user_agent, :log_datetime, :url, :memory_volume)";

        $handler = Application::$storage->get()->prepare($logSql);
        $handler->execute([
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'log_datetime' => date("Y-m-d H:i:s", $_SERVER['REQUEST_TIME']),
            'url' => $_SERVER['REQUEST_URI'],
            'memory_volume' => $memory
        ]);
    }

    public function run()
    {
        session_start();
        //Application::$auth->cookieAuth();

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
                        $_SESSION['counter']++;
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
                $logMessage = "Метод " . $this->methodName . " не существует в контроллере " . $this->controllerName . " | ";
                $logMessage .= "Попытка вызова адреса " . $_SERVER['REQUEST_URI'];
                Application::$logger->error($logMessage);

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

        $rules[] = 'admin';
        $isAllowed = false; //В лекции был false
        if (!empty($rules)) {
            foreach ($rules as $rolePermission) {
                if (in_array($rolePermission, $userRoles)) {
                    $isAllowed = true;
                    break;
                }
            }
        }
        return $isAllowed;
    }
}
