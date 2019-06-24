<?php
namespace Mini\Contracts;

trait ApplicationAwareTrait {

    protected $app;

    public function setApplication($app) 
    {
        $this->app = $app;
    }
}