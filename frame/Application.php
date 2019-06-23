<?php

namespace Mini;

use Mini\Core\Container;
use Mini\Contracts\ConfigInterface;

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
    }


    protected function registerInstance() 
    {
        $this->instance("path", $this->basePath());
        $this->instance("path.config", $this->configPath());
        $this->instance("config", $this->config);
        $this->instance("app", $this);
        $this->instance("Container", $this);
    }

    protected function loadConfig() 
    {
        return $this->make(ConfigInterface::class, ["basepath" => $this->configPath()]);
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