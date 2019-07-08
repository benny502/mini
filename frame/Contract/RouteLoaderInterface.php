<?php

namespace Mini\Contract;

use Symfony\Component\Routing\RouteCollection;

interface RouteLoaderInterface 
{
    public function load() : RouteCollection;
}