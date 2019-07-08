<?php
namespace Mini\Contract;

trait ApplicationAwareTrait {

    protected $app;

    public function setApplication($app) 
    {
        $this->app = $app;
    }
}