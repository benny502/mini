<?php

namespace Mini\Config;

use Mini\Contract\ConfigInterface;
use Mini\Contract\RouteLoaderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader implements RouteLoaderInterface
{

    protected $routes;

    public function __construct(ConfigInterface $config)
    {
        $this->routes = $config->get("routes");
    }


    /**
     * @return Symfony\Component\Routing\RouteCollection;
     */
    public function all() 
    {
        $collection = new RouteCollection;
        foreach($this->routes as $key=>$router) {

            $collection->add($key, new Route($router["path"], $router["defaults"]));
        }
        return $collection;
    }
}