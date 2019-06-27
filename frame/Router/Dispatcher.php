<?php
namespace Mini\Router;
use Symfony\Component\HttpFoundation\Request;
use Mini\Contract\RouteLoaderInterface;
use Mini\Contract\ApplicationAware;
use Mini\Contract\ApplicationAwareTrait;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Mini\Core\Pipeline;

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
        $middleware = $this->gatheredMiddleware($request);
        return $this->pipline->send($request)->through($middleware)->then(function($request) {
            return $request;
        });
    }

    protected function gatheredMiddleware($request) 
    {
        $groupMiddleware = is_null($request->attributes->get("_group")) ? [] : 
                $this->gatheredGroupMiddleware($request);
        $routeMiddleware = is_null($request->attributes->get("_middleware")) ? [] : 
                $this->gatheredRouteMiddleware($request);
        return array_merge($groupMiddleware, $routeMiddleware);
    }
    
    protected function gatheredGroupMiddleware($request) 
    {
        $key = $request->attributes->get("_group");
        if(isset($this->groupMiddleware[$key]) && !is_array($this->groupMiddleware[$key])) {
            throw LogicException("groupMiddleware must be an array");
        }
        return $this->groupMiddleware[$key] ?? [];
    }

    protected function gatheredRouteMiddleware($request)
    {
        return isset($this->routeMiddleware[$request->attributes->get("_middleware")]) ? 
                [$this->routeMiddleware[$request->attributes->get("_middleware")]] : [];
    }
}