<?php
use Mini\Core\Container;
use Mini\Contract\Config;
use Mini\Contract\ConfigInterface;

if(!function_exists('container')) {
    function container($abstract = null, $parameters = null) {
        if(is_null($abstract)) {
            return Container::getInstance();
        }
        return Container::getInstance()->make($abstract, $parameters);
    }
}


if(!function_exists('config_path')) {
    function config_path() {
        return Container::getInstance()->make('path.config');
    }
}

if(!function_exists('config')) {
    function config($abstract) {
        return Container::getInstance()->make(ConfigInterfaces::class, config_path())->get($abstract);
    }
}
