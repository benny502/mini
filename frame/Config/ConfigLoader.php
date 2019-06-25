<?php

namespace Mini\Config;
use Noodlehaus\Config;
use Mini\Contract\ConfigLoaderInterface;

class ConfigLoader implements ConfigLoaderInterface
{

    protected $basePath;
    protected $extentions;

    public function __construct($basePath, $extentions = '.yml')
    {   
       $this->basePath = $basePath; 
       $this->extentions = $extentions;
    }

    public function load(string $abstact) 
    {
        if(!is_null($abstact) && is_string($abstact) && !empty($abstact)) {
            $stack = explode('.', $abstact);
            $filename = array_shift($stack);
            $config = $this->loadConfigFile($filename);
            if(count($stack) > 0) {
                $parameter = join('.', $stack);
                return $config->get($parameter);
            }
            return $config->all();
        }
        return null;
    }

    protected function loadConfigFile($filename) 
    {
        return Config::load($this->realPath($filename));
    }

    protected function realPath($filename) 
    {
        return $this->basePath.DIRECTORY_SEPARATOR.$filename.$this->extentions;
    }

}