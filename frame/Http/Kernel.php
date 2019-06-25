<?php

namespace Mini\Http;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\Request;
use Mini\Contract\RouteLoaderInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\HttpKernel;

class Kernel implements HttpKernelInterface
{

    protected $routeLoader;

    protected $controllerResolver;

    protected $argumentResolver;

    protected $httpKernel;

    public function __construct(HttpKernel $httpKernel, RouteLoaderInterface $routeLoader)
    {
        $this->routeLoader = $routeLoader;
        $this->httpKernel = $httpKernel;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {

        $routes = $this->routeLoader->load();
        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($routes, $context);
        $request->attributes->add($matcher->matchRequest($request));
        return $this->httpKernel->handle($request, $type, $catch);
    }

    protected function sendRequestThroughRoute($request) 
    {

    }
}