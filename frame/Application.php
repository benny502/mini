<?php

namespace Mini;

use Mini\Core\Container;
use Symfony\Component\HttpFoundation\Request;
use Mini\Contract\ConfigInterface;
use Mini\Contract\KernelInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Mini\Contract\RouteLoaderInterface;
use Mini\Core\Pipeline;

class Application extends Container {

    protected $basePath;

    protected $config;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    public function start() 
    {
        static::setInstance($this);
        $this->config = $this->loadConfig();
        $this->registerInstance();
        $this->registerServices();

        $request = Request::createFromGlobals();


        
        // $context = new RequestContext();
        // $context->fromRequest($request);
        // $matcher = new UrlMatcher($routes, $context);

        // $request->attributes->add($matcher->match($request->getPathInfo()));

        // $dispatcher = new EventDispatcher();

        // $controllerResolver = $this->make(ControllerResolverInterface::class);
        // $argumentResolver = new ArgumentResolver();

        $this->sendRequestThroughRoute($request);
        

        //$kernel = new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);
        

        //$response = $kernel->handle($request);
        
        //$response->send();
        
        //$kernel->terminate($request, $response);
    }

    protected function sendRequestThroughRoute($request) 
    {
        $pipline = new Pipeline($this);
        $pipline->send($request)
            ->through([])->then(function() {

            });
    }

    protected function registerInstance() 
    {
        $this->instance("path", $this->basePath());
        $this->instance("path.config", $this->configPath());
        $this->instance("config", $this->config);
        $this->instance("app", $this);
    }

    protected function registerServices() 
    {
        $serviceConfigs = $this->config->get("services.configs");
        foreach($serviceConfigs as $config) {
            $this->make($config)->register();
        }
    }

    protected function loadConfig() 
    {
        return $this->make(ConfigInterface::class, ["basePath" => $this->configPath()]);
    }

    public function basePath() 
    {
        return $this->basePath;
    }

    public function configPath() 
    {
        return $this->basePath.'config';
    }


}