<?php

namespace Mini;

use Mini\Core\Container;
use Mini\Contracts\Config;

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
    }


    protected function registerInstance() 
    {
        $this->instance("path", $this->basePath());
        $this->instance("path.config", $this->configPath());
        $this->instance("config", $this->config);
        $this->instance("app", $this);
    }

    protected function registerServices() 
    {
        $serviceConfigs = $this->config->get("services.configs");
        foreach($serviceConfigs as $config) {
            $this->make($config)->register();
        }
    }

    protected function loadConfig() 
    {
        return $this->make(Config::class, ["basePath" => $this->configPath()]);
    }

    public function basePath() 
    {
        return $this->basePath;
    }

    public function configPath() 
    {
        return $this->basePath.'config';
    }


}