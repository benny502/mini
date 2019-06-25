<?php

namespace Mini\Router;

use Mini\Contract\RouteLoaderInterface;
use Symfony\Component\Routing\RouteCollection;
use Mini\Contract\ApplicationAware;
use Mini\Contract\ApplicationAwareTrait;

class RouteLoader implements RouteLoaderInterface, ApplicationAware
{
    use ApplicationAwareTrait;

    /**
     * @return Symfony\Component\Routing\RouteCollection;
     */
    public function load() : RouteCollection
    {

        $loaders = $this->app->config("app.router.loader");
        $collection = new RouteCollection;
        foreach($loaders as $loader) 
        {
            $instance = $this->app->make($loader["method"]);
            $collection->addCollection($instance->load($loader['resource']));
        }
        return $collection;
    }

}