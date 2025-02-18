<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Controllers\Controller;

class PageController extends Controller
{
    public function actionIndex()
    {
        Application::$auth->cookieAuth();
        $render = new Render();
        return $render->renderPage('page-index.twig', ['title' => 'Главная страница']);
    }
}
