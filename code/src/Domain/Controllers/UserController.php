<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;

class UserController
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

    public function actionSave(): string
    {
        if (User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            $user->saveToStorage();
            $render = new Render();
            return $render->renderPage(
                
                    'user-created.twig',
                    ['title' => 'Пользователь создан',
                    'message' => 'Создан пользователь '. $user->getUserName() . $user->getUserLastName(),
            ]);
        } else {
            throw new \Exception("Переданные данные некорректны");
        }
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
                    'title' => 'Список пользователей в хранилище',
                    'users' => $users
                ]
            );
        }
    }
}
