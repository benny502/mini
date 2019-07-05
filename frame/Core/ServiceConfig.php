<?php

namespace Mini\Core;

use Mini\Contract\Configuration;
use Mini\Contract\KernelInterface;
use Mini\Contract\RouteLoaderInterface;
use Mini\Core\ControllerResolver;
use Mini\Core\Kernel;
use Mini\Router\RouteLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ServiceConfig extends Configuration
{
    public function register()
    {
        $this->app->bind(EventDispatcherInterface::class, EventDispatcher::class);
        $this->app->bind(ControllerResolverInterface::class, ControllerResolver::class);
        $this->app->bind(ArgumentResolverInterface::class, ArgumentResolver::class);
        $this->app->bind(KernelInterface::class, Kernel::class);

        $this->app->bind(RouteLoaderInterface::class, RouteLoader::class);
    }

}
