<?php

namespace Mini\Templating;

use Symfony\Component\HttpFoundation\Response;

interface TemplateLoaderInterface
{
    public function renderResponse($name, $context = [], Response $reposne = null);

    public function extension();
}
