<?php

namespace Geekbrains\Application1\Application;

use Exception;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Render
{
    private string $viewFolder = '/src/domain/Views/';
    private FilesystemLoader $loader;
    private Environment $environment;

    public function __construct()
    {
        $this->loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] .
            $this->viewFolder);
        $this->environment = new Environment($this->loader, [
            //   'cache' => $_SERVER['DOCUMENT_ROOT'] . '/cache/',
        ]);
        $this->environment->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Moscow');
    }
    public function renderPage(string $contentTemplateName = 'page-index.twig', array $templateVariables = [])
    {
        $template = $this->environment->load('main.twig');

        $templateVariables['content_template_name'] = $contentTemplateName;
        $templateVariables['header_template_name'] = 'header.twig';
        $templateVariables['footer_template_name'] = 'footer.twig';
        $templateVariables['navigation_template_name'] = 'navigation.twig';
        $templateVariables['sidebar_template_name'] = 'sidebar.twig';
        $templateVariables['counter'] = $_SESSION['counter'];

        if (isset($_SESSION['user_name'])) {
            $templateVariables['user_authorized'] = true;
            $templateVariables['authorized_name'] = $_SESSION['user_name'];
            $templateVariables['authorized_lastname_name'] = $_SESSION['user_lastname'];
        }

        return $template->render($templateVariables);
    }

    public function renderPageWithForm(string $contentTemplateName = 'page-index.twig', array $templateVariables = [])
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $templateVariables['csrf_token'] = $_SESSION['csrf_token'];

        return $this->renderPage($contentTemplateName, $templateVariables);
    }


    public static function renderExceptionPage(Exception $exception): string
    {

        $render = new Render();

        return $render->renderPage(
            'error.twig',
            [
                'title' => 'Сообщение об ошибке',
                'error_message' => $exception->getMessage()
            ]
        );
    }
}

//echo 123;