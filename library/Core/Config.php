<?php

namespace Mini\Core;
use Noodlehaus\Config as ConfigLoader;
use Mini\Contracts\ConfigInterface;

class Config implements ConfigInterface
{

    protected $basepath;
    protected $extentions = '.yml';

    public function __construct($basepath)
    {   
       $this->basepath = $basepath; 
    }

    public function get(string $abstact) 
    {
        if(!is_null($abstact) && is_string($abstact) && !empty($abstact)) {
            $stack = explode('.', $abstact);
            $filename = array_shift($stack);
            $config = $this->loadConfigFile($filename);
            $parameter = join('.', $stack);
            return $config->get($parameter);
        }
        return null;
    }

    protected function loadConfigFile($filename) 
    {
        return ConfigLoader::load($this->realPath($filename));
    }

    protected function realPath($filename) 
    {
        return $this->basepath.DIRECTORY_SEPARATOR.$filename.$this->extentions;
    }

}