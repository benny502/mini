<?php
namespace Mini\Router;

use Mini\Core\Pipeline;
use Mini\Contract\ApplicationAware;
use Mini\Contract\RouteLoaderInterface;
use Mini\Contract\ApplicationAwareTrait;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;

class Dispatcher implements ApplicationAware
{
    use ApplicationAwareTrait;

    protected $loader;

    protected $context;

    protected $pipline;

    protected $middleware = [];

    protected $groupMiddleware = [];

    protected $routeMiddleware = [];

    public function __construct(RouteLoaderInterface $loader, RequestContext $context, Pipeline $pipeline)
    {
        $this->loader = $loader;
        $this->context = $context;
        $this->pipline = $pipeline;
    }

    public function dispatch(Request $request, $type, $catch)
    {
        $routes = $this->loader->load();
        $context = $this->context->fromRequest($request);
        $matcher = new UrlMatcher($routes, $context);
        $request->attributes->add($matcher->matchRequest($request));
        return $this->sendThroughMiddleware($request, $type, $catch);
    }

    public function setMiddleware($middleware) {
        $this->middleware = [$middleware];
    }

    public function setGroupMiddleware($groupMiddleware)
    {
        $this->groupMiddleware = $groupMiddleware;
    }

    public function setRouteMiddleware($routeMiddleware)
    {
        $this->routeMiddleware = $routeMiddleware;
    }

    protected function sendThroughMiddleware($request, $type, $catch)
    {
        $middleware = $this->gatheredMiddleware($request);
        return $this->pipline->send($request)->through($middleware)->then(function ($request) use($type, $catch) {
            return $this->getHttpKernel()->handle($request, $type, $catch);
        });
    }

    protected function gatheredMiddleware($request)
    {
        $groupMiddleware = $this->gatheredGroupMiddleware($request);
        $routeMiddleware = $this->gatheredRouteMiddleware($request);
        return array_merge($this->middleware, $groupMiddleware, $routeMiddleware);
    }

    protected function gatheredGroupMiddleware($request)
    {
        $key = $request->attributes->get("_group");
        if (is_null($key)) {
            return [];
        }

        if (!is_array($this->groupMiddleware)) {
            throw new \RuntimeException("groupMiddleware must be an array");
        }
        if (!array_key_exists($key, $this->groupMiddleware)) {
            throw new \RuntimeException("groupMiddleware [$key] does not exist");
        }

        if (isset($this->groupMiddleware[$key]) && !is_array($this->groupMiddleware[$key])) {
            throw new \RuntimeException("groupMiddleware [$key] must be an array");
        }
        return $this->groupMiddleware[$key] ?? [];
    }

    protected function gatheredRouteMiddleware($request)
    {
        $key = $request->attributes->get("_middleware");
        if (is_null($key)) {
            return [];
        }

        if (!is_array($this->routeMiddleware)) {
            throw new \RuntimeException("routeMiddleware must be an array");
        }
        if (!array_key_exists($key, $this->routeMiddleware)) {
            throw new \RuntimeException("routeMiddleware [$key] does not exist");
        }

        if (!isset($this->routeMiddleware[$key])) {
            throw new \RuntimeException("routeMiddleware [$key] cannot be empty");
        }
        return [$this->routeMiddleware[$key]];
    }

    protected function getHttpKernel()
    {
        return $this->app->make(HttpKernel::class);
    }
}
