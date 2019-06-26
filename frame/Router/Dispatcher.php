<?php
namespace Mini\Router;
use Symfony\Component\HttpFoundation\Request;
use Mini\Contract\RouteLoaderInterface;
use Mini\Contract\ApplicationAware;
use Mini\Contract\ApplicationAwareTrait;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Mini\Core\Pipeline;
use Illuminate\Support\Collection;

class Dispatcher implements ApplicationAware
{
    use ApplicationAwareTrait;

    protected $loader;

    protected $context;

    protected $pipline;

    protected $groupMiddleware = [];

    protected $routeMiddleware = [];

    public function __construct(RouteLoaderInterface $loader, RequestContext $context, Pipeline $pipeline)
    {
        $this->loader = $loader;
        $this->context = $context;
        $this->pipline = $pipeline;
    }

    public function dispatch(Request $request) 
    {
        $routes = $this->loader->load();
        $context = $this->context->fromRequest($request);
        $matcher = new UrlMatcher($routes, $context);
        $request->attributes->add($matcher->matchRequest($request));
        return $this->sendThroughMiddleware($request);
    }
    
    public function setGroupMiddleware($groupMiddleware) 
    {
        $this->groupMiddleware = $groupMiddleware;
    }

    public function setRouteMiddleware($routeMiddleware)
    {
        $this->routeMiddleware = $routeMiddleware;
    }

    protected function sendThroughMiddleware($request) 
    {   
        $middleware = $this->gatheredMiddleware();
        return $request;
    }

    protected function gatheredMiddleware() {
        //$collection = $this->
    }
}