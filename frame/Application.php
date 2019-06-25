<?php

namespace Mini;

use Mini\Core\Container;
use Symfony\Component\HttpFoundation\Request;
use Mini\Contract\ConfigInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Application extends Container {

    protected $basePath;

    protected $config;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    public function start() 
    {
        static::setInstance($this);
        $this->config = $this->loadConfig();
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
        $this->instance("config", $this->config);
        $this->instance("app", $this);
    }

    protected function registerServices() 
    {
        $serviceConfigs = $this->config->load("services.configs");
        foreach($serviceConfigs as $config) {
            $this->make($config)->register();
        }
    }

    protected function loadConfig() 
    {
        return $this->make(ConfigInterface::class, ["basePath" => $this->configPath()]);
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
        return $this->config->load($abstract);
    }


}