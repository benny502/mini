<?php

namespace Mini\Core;

use Mini\Contract\ApplicationAware;
use Mini\Contract\ApplicationAwareTrait;
use Mini\Contract\KernelInterface;
use Mini\Http\Kernel;
use Mini\Contract\RouteLoaderInterface;
use Mini\Config\RouteLoader;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Mini\Http\ControllerResolver;

class ServiceConfig implements ApplicationAware {

    use ApplicationAwareTrait;

    public function register() 
    {
        $this->app->bind(KernelInterface::class, Kernel::class);
        $this->app->bind(RouteLoaderInterface::class, function () {
            return new RouteLoader($this->app->make('config'));
        });

        $this->app->bind(ControllerResolverInterface::class, ControllerResolver::class);
    }

}