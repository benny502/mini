<?php

namespace Mini\Router;

use Symfony\Component\Routing\Loader\AnnotationClassLoader as SymfonyAnnotationClassLoader;
use Symfony\Component\Routing\Route;

class AnnotationClassLoader extends SymfonyAnnotationClassLoader {

    /**
     * Configures the _controller default parameter and eventually the HTTP method
     * requirement of a given Route instance.
     *
     * @param mixed $annot The annotation class instance
     *
     * @throws \LogicException When the service option is specified on a method
     */
    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, $annot)
    {
        // controller
        if ('__invoke' === $method->getName()) {
            $route->setDefault('_controller', $class->getName());
        } else {
            $route->setDefault('_controller', $class->getName().'::'.$method->getName());
        }

        // requirements (@Method)
        foreach ($this->reader->getMethodAnnotations($method) as $configuration) {
            if ($configuration instanceof Method) {
                $route->setMethods($configuration->getMethods());
            } elseif ($configuration instanceof FrameworkExtraBundleRoute && $configuration->getService()) {
                throw new \LogicException('The service option can only be specified at class level.');
            }
        }
    }

    protected function getGlobals(\ReflectionClass $class)
    {
        $globals = parent::getGlobals($class);

        foreach ($this->reader->getClassAnnotations($class) as $configuration) {
            if ($configuration instanceof Method) {
                $globals['methods'] = array_merge($globals['methods'], $configuration->getMethods());
            }
        }

        return $globals;
    }

    /**
     * Makes the default route name more sane by removing common keywords.
     *
     * @return string The default route name
     */
    protected function getDefaultRouteName(\ReflectionClass $class, \ReflectionMethod $method)
    {
        $routeName = parent::getDefaultRouteName($class, $method);

        return preg_replace(
            ['/(bundle|controller)_/', '/action(_\d+)?$/', '/__/'],
            ['_', '\\1', '_'],
            $routeName
        );
    }

}