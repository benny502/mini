<?php

namespace App\Controllers;

use Mini\Contract\ApplicationAware;
use Mini\Contract\ApplicationAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Exception\LogicException;

class Controller implements ApplicationAware
{
    use ApplicationAwareTrait;

    public function redirect($path)
    {
        return new RedirectResponse($path);
    }

    public function render($name, $context = [], Response $response = null) 
    {
        if(!$this->app->has("templating")) {
            throw new LogicException("The template is not available");
        }

        $templating = $this->app->make("templating");

        return $templating->renderResponse($name, $context, $response);
    }

}
