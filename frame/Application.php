<?php

namespace Mini;

use Illuminate\Container\Container;
use Mini\Contract\ApplicationAware;

class Application extends Container
{

    protected $basePath;

    protected $loader;

    public function __construct($basePath = "")
    {
        static::setInstance($this);
        if (!empty($basePath)) {
            $this->basePath = $basePath;
        }
        $this->registerInstance();
    }

    public function make($abstract, array $parameters = [])
    {
        $object = parent::make($abstract, $parameters);
        if ($object instanceof ApplicationAware) {
            $object->setApplication($this);
        }
        return $object;
    }

    protected function registerInstance()
    {
        $this->instance("path", $this->basePath());
        $this->instance("path.config", $this->configPath());
        $this->instance("path.app", $this->appPath());
        $this->instance("app", $this);
    }

    public function basePath()
    {
        return $this->basePath;
    }

    public function configPath()
    {
        return $this->basePath . "config";
    }

    public function appPath()
    {
        return $this->basePath . "app";
    }

}
