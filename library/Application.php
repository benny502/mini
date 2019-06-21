<?php

namespace Mini;

use Mini\Core\Container;
use Mini\Contracts\ConfigInterface;

class Application extends Container {

    protected $basePath;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    public function start() 
    {
        $this->registerInstance();
        echo config('database.mysql.host');

    }


    protected function registerInstance() {
        $this->instance("path", $this->basePath());
        $this->instance("path.config", $this->configPath());
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