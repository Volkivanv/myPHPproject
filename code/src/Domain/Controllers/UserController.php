<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;

class UserController
{
    public function actionSave()
    {
        echo("добавление");


        $users = User::addUserFromRequest();
        $render = new Render();
        return $render->renderPage(
            'user-index.twig',
            [
                'title' => 'Список пользователей в хранилище',
                'users' => $users
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
                    'title' => 'Список пользователей в хранилище',
                    'users' => $users
                ]
            );
        }
    }
}
