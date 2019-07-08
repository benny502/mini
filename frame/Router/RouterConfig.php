<?php

namespace MIni\Router;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Mini\Router\AnnotationClassLoader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Mini\Contract\Configuration;

class RouterConfig extends Configuration
{
    public function register() 
    {
        $this->app->bind("router.loader.file", function() {
            $locator = new FileLocator($this->app->make("path.config"));
            return new YamlFileLoader($locator);
        });

        $this->app->bind(Reader::class, AnnotationReader::class);

        $this->app->bind("router.loader.annotation", function() {
            AnnotationRegistry::registerLoader('class_exists');
            $locator = new FileLocator($this->app->make("path.app").DIRECTORY_SEPARATOR."Controllers");
            return new AnnotationDirectoryLoader($locator, $this->app->make(AnnotationClassLoader::class));
        });
    }

}