<?php

namespace Geekbrains\Application1;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Render
{
    private string $viewFolder = '/src/Views/';
    private FilesystemLoader $loader;
    private Environment $environment;

    public function __construct()
    {
        $this->loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] .
            $this->viewFolder);
        $this->environment = new Environment($this->loader, [
         //   'cache' => $_SERVER['DOCUMENT_ROOT'] . '/cache/',
        ]);
    }
    public function renderPage(string $contentTemplateName = 'page-index.twig', array $templateVariables = [])
    {
        $template = $this->environment->load('main.twig');
        
        $templateVariables['content_template_name'] = $contentTemplateName;
        $templateVariables['header_template_name'] = 'header.twig';
        $templateVariables['footer_template_name'] = 'footer.twig';
        $templateVariables['navigation_template_name'] = 'navigation.twig';
        $templateVariables['sidebar_template_name'] = 'sidebar.twig';
        return $template->render($templateVariables);
    }
}

//echo 123;