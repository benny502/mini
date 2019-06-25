<?php

namespace Mini;

use Mini\Core\Container;
use Symfony\Component\HttpFoundation\Request;
use Mini\Contract\ConfigLoaderInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Application extends Container {

    protected $basePath;

    protected $loader;

    public function __construct($basePath, ConfigLoaderInterface $loader)
    {
        $this->basePath = $basePath;
        $this->loader = $loader;
    }

    public function start() 
    {
        static::setInstance($this);
        $this->registerInstance();
        $this->registerServices();

        $request = Request::createFromGlobals();

        $kernel  = $this->make(HttpKernelInterface::class);

        $response = $kernel->handle($request);
        
        $response->send();
        
        $kernel->terminate($request, $response);
    }

    protected function registerInstance() 
    {
        $this->instance("path", $this->basePath());
        $this->instance("path.config", $this->configPath());
        $this->instance("path.app", $this->appPath());
        $this->instance("app", $this);
    }

    protected function registerServices() 
    {
        $serviceConfigs = $this->loader->load("services.configs");
        foreach($serviceConfigs as $config) {
            $this->make($config)->register();
        }
    }

    public function basePath() 
    {
        return $this->basePath;
    }

    public function configPath() 
    {
        return $this->basePath."config";
    }

    public function appPath() {
        return $this->basePath."app";
    }

    public function config($abstract) 
    {
        return $this->loader->load($abstract);
    }


}