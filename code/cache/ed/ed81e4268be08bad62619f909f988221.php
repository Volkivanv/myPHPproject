<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* main.twig */
class __TwigTemplate_9cecf05daa20817bf80e2e082bba2aee extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<!DOCTYPE html>
<html>
\t<head>
\t\t<link rel=\"stylesheet\" href=\"/css/styles.css\">
\t\t<title>";
        // line 5
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["title"] ?? null), "html", null, true);
        yield "</title>
\t</head>
\t<body>
\t\t<div class=\"top container\">
\t\t\t<header class=\"header\">";
        // line 9
        yield from $this->loadTemplate(($context["header_template_name"] ?? null), "main.twig", 9)->unwrap()->yield($context);
        yield "</header>
\t\t\t<nav class=\"navigation\">";
        // line 10
        yield from $this->loadTemplate(($context["navigation_template_name"] ?? null), "main.twig", 10)->unwrap()->yield($context);
        yield "</nav>
\t\t</div>
\t\t<div class=\"middle container\">
\t\t\t<main class=\"main\">";
        // line 13
        yield from $this->loadTemplate(($context["content_template_name"] ?? null), "main.twig", 13)->unwrap()->yield($context);
        yield "</main>
\t\t\t<aside class=\"sidebar\">";
        // line 14
        yield from $this->loadTemplate(($context["sidebar_template_name"] ?? null), "main.twig", 14)->unwrap()->yield($context);
        yield "</aside>
\t\t</div>
\t\t<footer class=\"footer container\">";
        // line 16
        yield from $this->loadTemplate(($context["footer_template_name"] ?? null), "main.twig", 16)->unwrap()->yield($context);
        yield "</footer>
\t</body>
</html>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "main.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  74 => 16,  69 => 14,  65 => 13,  59 => 10,  55 => 9,  48 => 5,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "main.twig", "/data/mysite.local/src/Domain/Views/main.twig");
    }
}
