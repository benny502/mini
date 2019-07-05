<?php
use Illuminate\Container\Container;
use Mini\Contract\ConfigLoaderInterface;

if (!function_exists('container')) {
    function container($abstract = null, $parameters = null)
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }
        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('config_path')) {
    function config_path()
    {
        return Container::getInstance()->make('path.config');
    }
}

if (!function_exists('config')) {
    function config($abstract)
    {
        return Container::getInstance()->make(ConfigLoaderInterface::class, config_path())->load($abstract);
    }
}
