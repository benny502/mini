<?php

namespace Mini\Core;

use Mini\Contract\ApplicationAware;
use Mini\Contract\ApplicationAwareTrait;
use Mini\Contract\ConfigLoaderInterface;
use Mini\Contract\KernelInterface;
use Mini\Core\Pipeline;
use Mini\Router\Dispatcher;
use Mini\Templating\TemplateLoaderInterface;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernel;

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
        $this->registerConfiguredServices();
        $this->loadExceptionHandle();
        $this->loadMiddleware();
        $this->loadTemplate();
        $request = $this->sendRequestThroughRouter($request);
        return $this->getHttpKernel()->handle($request, $type, $catch);
    }

    public function getAppPath()
    {
        return $this->app->make("path.app");
    }

    protected function loadTemplate()
    {
        $this->app->instance("templating", $this->app->make(TemplateLoaderInterface::class));
    }

    protected function registerConfiguredServices()
    {
        $serviceConfigs = $this->configLoader->load("services.configs");
        foreach ($serviceConfigs as $config) {
            $this->app->make($config)->register();
        }

        if (null !== $services = $this->registerServices()) {
            foreach ($services as $key => $service) {
                $this->app->bind($key, $service);
            }
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
        return function ($request) {
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
