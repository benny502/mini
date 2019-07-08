<?php
namespace App;

use Mini\Core\Kernel;
use Mini\Templating\TemplateLoaderInterface;
use Mini\Templating\TwigLoader;

class AppKernel extends Kernel
{
    public function rootPath()
    {
        return __DIR__;
    }

    public function viewPath()
    {
        return $this->rootPath() . DIRECTORY_SEPARATOR . "Views";
    }

    public function cachePath()
    {
        return $this->app->basePath() . "cache";
    }

    protected function registerServices()
    {
        return [
            TemplateLoaderInterface::class => function () {
                return new TwigLoader($this->viewPath(), $this->cachePath());
            },
        ];
    }
}
