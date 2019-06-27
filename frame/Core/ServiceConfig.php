<?php

namespace Mini\Core;

use Mini\Contract\RouteLoaderInterface;
use Mini\Router\RouteLoader;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Mini\Core\ControllerResolver;
use Mini\Contract\KernelInterface;
use Mini\Core\Kernel;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Mini\Contract\Configuration;

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