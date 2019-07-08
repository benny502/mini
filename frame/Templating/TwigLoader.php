<?php

namespace Mini\Templating;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigLoader implements TemplateLoaderInterface
{
    protected $loader;

    protected $twig;

    public function __construct($path, $cache)
    {
        $this->loader = new FilesystemLoader($path);
        $this->twig = new Environment($this->loader, [
            'cache' => $cache,
        ]);
    }

    public function renderResponse($name, $context = [], Response $response = null)
    {
        $content = $this->twig->render($name . "." . $this->extension(), $context);

        if (is_null($response)) {
            $response = new Response();
        }
        $response->setContent($content);
        return $response;
    }

    public function extension()
    {
        return "twig";
    }
}
