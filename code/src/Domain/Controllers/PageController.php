<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Render;

class PageController
{
    public function actionIndex()
    {
        $render = new Render();
     //   print_r(Application::config()['storage']['address']);
        return $render->renderPage('page-index.twig', ['title' => 'Главная страница']);
    }
}
