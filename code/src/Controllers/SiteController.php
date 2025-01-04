<?php 

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\Phone;
use Geekbrains\Application1\Models\SiteInfo;
class SiteController
{
    public function actionIndex(){

         $siteName = (new SiteInfo())->getName();
         echo $siteName;
         $render = new Render();
         return $render->renderPage('site-index.twig', ['sitename'=> $siteName]);
    }
    public function actionInfo(){

         $siteInfo = (new SiteInfo())->getInfo();
         $render = new Render();
         return $render->renderPage('site-info.twig', $siteInfo);
    }
}