<?php 

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\SiteInfo;
class SiteController
{
    public function actionIndex(){

         $siteName = (new SiteInfo())->getName();
       //  echo $siteName;
         $render = new Render();
         return $render->renderPage('site-index.twig', ['sitename'=> $siteName]);
    }
    public function actionInfo(){

         $siteInfo = (new SiteInfo())->getInfo();
         $render = new Render();
         return $render->renderPage('site-info.twig',[
          'webserver' => $siteInfo['webserver'],
          'interpretator' => $siteInfo['interpretator'],
          'browser' => $siteInfo['browser'],
         ]);
    }
}