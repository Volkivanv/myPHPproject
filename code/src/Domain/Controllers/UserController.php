<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;
use Geekbrains\Application1\Application\Auth;
use Geekbrains\Application1\Domain\Controllers\AbstractController;


class UserController extends AbstractController
{
    // добавление в текстовый файл
    // public function actionSave()
    // {
    //     echo("добавление");


    //     $users = User::addUserFromRequest();
    //     $render = new Render();
    //     return $render->renderPage(
    //         'user-index.twig',
    //         [
    //             'title' => 'Список пользователей в хранилище',
    //             'users' => $users
    //         ]
    //     );
    // }

    protected array $actionsPermissions = [
        'actonHash' => ['admin', 'manager'],
        'actionSave' => ['admin'],
        'actionEdit' => ['admin'],
        //   'actionAuth'=> ['admin', 'manager', null],
        'actionIndex' => ['admin'],
        'actionLogout' => ['admin'],
    ];

    public function actionSave(): string
    {
        if (User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            $user->saveToStorage();
            $render = new Render();
            return $render->renderPage(

                'user-created.twig',
                [
                    'title' => 'Пользователь создан',
                    'message' => 'Создан пользователь ' . $user->getUserName() . ' ' . $user->getUserLastName(),
                ]
            );
        } else {
            throw new \Exception("Переданные данные некорректны");
        }
    }

    public function actionUpdate(): string
    {
        $id = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : 0;
        if (User::exists($id)) {
            $user = new User(); //Active recort
            $user->setUserId($id);

            $user->updateUser(User::setArrayDataFromRequest());
            // var_dump($user);
        } else {
            return new \Exception("Пользователь не существует");
        }

        $render = new Render();
        return $render->renderPage(
            'user-created.twig',
            [
                'title' => 'Пользователь обновлен',
                'message' => "Обновлен пользователь " . $user->getUserId()
            ]
        );
    }
    public function actionIndex()
    {
        $users = User::getAllUsersFromStorage();
        $render = new Render();
        if (!$users) {
            return $render->renderPage(
                'user-empty.twig',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => "Список не найден"
                ]
            );
        } else {
            return $render->renderPage(
                'user-index.twig',
                [
                    'title' => 'Список пользователей',
                    'users' => $users
                ]
            );
        }
    }

    public function actionShow()
    {
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;



        if (User::exists($id)) {
            $user = User::getUserFromStorageById($id);
            $render = new Render();
            return $render->renderPage(
                'user-page.twig',
                [
                    'title' => 'Выбранный пользователь',
                    'user' => $user
                ]
            );
        } else {
            throw new \Exception("Пользователь не существует");
        }
    }

    public function actionDelete(): string
    {
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
        if (User::exists($id)) {
            User::deleteFromStorage($id);

            $render = new Render();

            return $render->renderPage(
                'user-removed.twig',
                []
            );
        } else {
            throw new \Exception("Пользователь не существует");
        }
    }

    public function actionEdit(): string
    {
        $render = new Render();
        return $render->renderPageWithForm(
            'user-form.twig',
            [
                'title' => 'Форма создания пользователя',
                'action_user' => '/user/save/',
                'button_name' => 'Создать'
            ]
        );
    }

    public function actionPrepare(): string
    {


        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;



        if (User::exists($id)) {
            $user = User::getUserFromStorageById($id);
            $render = new Render();
            return $render->renderPageWithForm(
                'user-form.twig',
                [
                    'title' => 'Форма обновления пользователя',
                    'user' => $user,
                    'action_user' => '/user/update/',
                    'button_name' => 'Обновить'
                ]
            );
        } else {
            throw new \Exception("Пользователь не существует");
        }
    }

    public function actionHash(): string
    {
        if (isset($_GET['pass_string']) && !empty($_GET['pass_string'])) {
            return Auth::getPasswordHash($_GET['pass_string']);
        } else {
            throw new \Exception("Невозможно сгенерировать хэш. Не передан
            пароль");
        }
    }

    public function actionAuth(): string
    {
        $render = new Render();
        return $render->renderPageWithForm(
            'user-auth.twig',
            [
                'title' => 'Форма логина'
            ]
        );
    }

    public function actionLogin(): string
    {
        $result = false;
        $flagChecked = false;
        if (isset($_POST['login']) && isset($_POST['password'])) {
            if (isset($_POST["user-remember"])) {
                if ($_POST["user-remember"] === "remember") {
                    $_SESSION['random_bytes'] = random_bytes(200);
                    setcookie('random_bytes', $_SESSION['random_bytes'], time() + 24 * 3600, '/');
                }
            }

            $result = Application::$auth->proceedAuth(
                $_POST['login'],
                $_POST['password']
            );
        }
        if (!$result) {
            $render = new Render();
            return $render->renderPageWithForm(
                'user-auth.twig',
                [
                    'title' => 'Форма логина',
                    'auth-success' => false,
                    'auth-error' => 'Неверные логин или пароль'
                ]
            );
        } else {
            if (isset($_SESSION['random_bytes'])) {
                $user = new User();
                $user->setUserId($_SESSION['auth']['id_user']);
                $user->setRandomBytes($_SESSION['random_bytes']);

                setcookie('id_user', $_SESSION['auth']['id_user'], time() + 24 * 3600, '/');
            }
            header('Location: /');
            return "";
        }
    }

    public function actionExit()
    {
        setcookie('id_user', "", time() - 3600, '/');

        setcookie('random_bytes', "", time() - 3600, '/');
        session_destroy();
        unset($_SESSION['auth']);

        header("Location: /");
        die();
    }
}
