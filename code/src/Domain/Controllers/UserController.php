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
        'actonHash'=> ['admin', 'manager'],
        'actionSave'=> ['admin'],
 //       'actionAuth'=> ['admin', 'manager', null]
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
            return Render::renderExceptionPage(new \Exception("Переданные данные некорректны"));
        }
    }

    public function actionUpdate(): string
    {
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
        if (User::exists($id)) {
            $user = new User();
            $user->setUserId($id);

            $arrayData = [];

            if (isset($_GET['name']))
                $arrayData['user_name'] = $_GET['name'];

            if (isset($_GET['lastname'])) {
                $arrayData['user_lastname'] = $_GET['lastname'];
            }

            $user->updateUser($arrayData);
        } else {
            return Render::renderExceptionPage(new \Exception("Пользователь не существует"));
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
            return Render::renderExceptionPage(new \Exception("Пользователь не существует"));
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
            return Render::renderExceptionPage(new \Exception("Пользователь не существует"));
        }
    }

    public function actionEdit(): string
    {
        $render = new Render();
        return $render->renderPageWithForm(
            'user-form.twig',
            [
                'title' => 'Форма создания пользователя'
            ]
        );
    }

    public function actionHash(): string
    {
        return Auth::getPasswordHash($_GET['pass_string']);
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
        if (isset($_POST['login']) && isset($_POST['password'])) {
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
            header('Location: /');
            return "";
        }
    }
}
