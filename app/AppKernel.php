<?php
namespace App;

use Mini\Core\Kernel;
use Mini\Templating\TemplateLoaderInterface;
use Mini\Templating\TwigLoader;

class AppKernel extends Kernel
{
    public function getRoot()
    {
        return __DIR__;
    }

    public function getViewPath()
    {
        return $this->getRoot() . DIRECTORY_SEPARATOR . "Views";
    }

    public function getCachePath()
    {
        return $this->getRoot() . DIRECTORY_SEPARATOR . "Cache";
    }

    protected function registerServices()
    {
        return [
            TemplateLoaderInterface::class => function () {
                return new TwigLoader($this->getViewPath(), $this->getCachePath());
            },
        ];
    }
}
