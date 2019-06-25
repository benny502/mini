<?php

namespace Mini\Core;

use Mini\Contract\ApplicationAware;
use Mini\Contract\ApplicationAwareTrait;
use Mini\Contract\RouteLoaderInterface;
use Mini\Router\RouteLoader;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Mini\Http\ControllerResolver;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Mini\Http\Kernel;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ServiceConfig implements ApplicationAware {

    use ApplicationAwareTrait;

    public function register() 
    {
        $this->app->bind(EventDispatcherInterface::class, EventDispatcher::class);
        $this->app->bind(ControllerResolverInterface::class, ControllerResolver::class);
        $this->app->bind(ArgumentResolverInterface::class, ArgumentResolver::class);
        $this->app->bind(HttpKernelInterface::class, Kernel::class);

        $this->app->bind(RouteLoaderInterface::class, RouteLoader::class);
    }

}