<?php

namespace App\Controllers;

use Mini\Contract\ApplicationAware;
use Mini\Contract\ApplicationAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Controller implements ApplicationAware
{
    use ApplicationAwareTrait;

    public function redirect($path) 
    {
        return new RedirectResponse($path);
    }
}