<?php 

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\Phone;
class AboutController
{
    public function actionIndex(){
        $phone = (new Phone())->getPhone();
        $render = new Render();
        return $render->renderPage('about.twig', [
            'phone' => $phone,
        ]);
    }
}