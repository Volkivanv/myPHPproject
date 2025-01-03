<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;

class PageController
{
    public function actionIndex()
    {
        $render = new Render();

        return $render->renderPage();
    }
}
