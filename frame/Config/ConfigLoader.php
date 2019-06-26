<?php

namespace Mini\Config;
use Noodlehaus\Config;
use Mini\Contract\ConfigLoaderInterface;
use Mini\Contract\ApplicationAware;
use Mini\Contract\ApplicationAwareTrait;

class ConfigLoader implements ConfigLoaderInterface, ApplicationAware
{
    use ApplicationAwareTrait;

    protected $extention = "yml";

    public function setExtension(string $extention)
    {
        $this->extention = $extention;
    }

    public function getExtension() 
    {
        return $this->extention;
    }

    public function support() 
    {
        return $this->extention;
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
        return $this->app->configPath().DIRECTORY_SEPARATOR.$filename.".".$this->extention;
    }

}