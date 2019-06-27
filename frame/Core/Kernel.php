<?php

namespace Mini\Core;

use Symfony\Component\HttpFoundation\Request;
use Mini\Contract\KernelInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use Mini\Contract\ConfigLoaderInterface;
use Mini\Contract\ApplicationAware;
use Mini\Contract\ApplicationAwareTrait;
use Mini\Core\Pipeline;
use Mini\Router\Dispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\Debug;

class Kernel implements KernelInterface, ApplicationAware
{
    use ApplicationAwareTrait;

    protected $routeLoader;

    protected $controllerResolver;

    protected $argumentResolver;

    protected $httpKernel;

    protected $configLoader;

    protected $pipline;

    protected $middleware = [];

    protected $routeMiddleware = [];

    protected $groupMiddleware = [];

    public function __construct(ConfigLoaderInterface $configLoader, Pipeline $pipeline)
    {
        $this->configLoader = $configLoader;
        $this->pipline = $pipeline;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $this->registerConfiguredsServices();
        $this->loadExceptionHandle();
        $this->loadMiddleware();
        $request = $this->sendRequestThroughRouter($request);
        return $this->getHttpKernel()->handle($request, $type, $catch);
    }

    protected function registerConfiguredsServices() 
    {
        $serviceConfigs = $this->configLoader->load("services.configs");
        foreach($serviceConfigs as $config) {
            $this->app->make($config)->register();
        }
    }

    protected function loadExceptionHandle() 
    {
        Debug::enable();
    }

    protected function loadMiddleware() 
    {
        $this->middleware = $this->configLoader->load("app.middleware") ?? [];
        $this->groupMiddleware = $this->configLoader->load("app.groupMiddleware") ?? [];
        $this->routeMiddleware = $this->configLoader->load("app.routeMiddleware") ?? [];
    }

    protected function sendRequestThroughRouter($request) 
    {
        return $this->pipline->send($request)->through($this->middleware)
            ->then($this->dispatchToRouter());
    }

    protected function dispatchToRouter() 
    {
        return function($request) {
            $dispatcher = $this->app->make(Dispatcher::class);
            $dispatcher->setGroupMiddleware($this->groupMiddleware);
            $dispatcher->setRouteMiddleware($this->routeMiddleware);
            return $dispatcher->dispatch($request);
        };
    }

    protected function getHttpKernel() 
    {
        return $this->app->make(HttpKernel::class);
    }

}