<?php

namespace Mini\Http;

use Mini\Contract\KernelInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Mini\Contract\RouteLoaderInterface;

class Kernel implements KernelInterface
{

    protected $routeLoader;

    public function __construct(RouteLoaderInterface $routeLoader)
    {
        $this->routeLoader = $routeLoader;
    }

    public function handle(Request $request)
    {

        $routes = $this->routeLoader->all();
        
        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($routes, $context);
        var_dump($matcher->matchRequest($request));
    }
}