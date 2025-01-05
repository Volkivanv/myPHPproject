<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Application;
use Geekbrains\Application1\Render;

class PageController
{
    public function actionIndex()
    {
        $render = new Render();
        print_r(Application::config()['storage']['address']);
        return $render->renderPage('page-index.twig', ['title' => 'Главная страница']);
    }
}
