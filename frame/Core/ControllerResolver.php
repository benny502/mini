<?php

namespace Mini\Core;

use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Mini\Contract\ApplicationAware;
use Mini\Contract\ApplicationAwareTrait;
use Psr\Log\LoggerInterface;

class ControllerResolver implements ControllerResolverInterface, ApplicationAware 
{
    use ApplicationAwareTrait;

    private $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function getController(Request $request)
    {
        if (!$controller = $request->attributes->get('_controller')) {
            if (null !== $this->logger) {
                $this->logger->warning('Unable to look for the controller as the "_controller" parameter is missing.');
            }

            return false;
        }

        if (\is_array($controller)) {
            if (isset($controller[0]) && \is_string($controller[0]) && isset($controller[1])) {
                $controller[0] = $this->instantiateController($controller[0]);
            }

            if (!\is_callable($controller)) {
                throw new \InvalidArgumentException(sprintf('The controller for URI "%s" is not callable. %s', $request->getPathInfo(), $this->getControllerError($controller)));
            }

            return $controller;

        }

        $callable = $this->createController($controller);

        if (!\is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf('The controller for URI "%s" is not callable. %s', $request->getPathInfo(), $this->getControllerError($callable)));
        }

        return $callable;

    }

    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            return $this->instantiateController($controller);
        }

        list($class, $method) = explode('::', $controller, 2);

        return [$this->instantiateController($class), $method];
    }

    protected function instantiateController($class)
    {
        return $this->app->make($class);
    }

    private function getControllerError($callable)
    {
        if (\is_string($callable)) {
            if (false !== strpos($callable, '::')) {
                $callable = explode('::', $callable, 2);
            } else {
                return sprintf('Function "%s" does not exist.', $callable);
            }
        }

        if (\is_object($callable)) {
            $availableMethods = $this->getClassMethodsWithoutMagicMethods($callable);
            $alternativeMsg = $availableMethods ? sprintf(' or use one of the available methods: "%s"', implode('", "', $availableMethods)) : '';

            return sprintf('Controller class "%s" cannot be called without a method name. You need to implement "__invoke"%s.', \get_class($callable), $alternativeMsg);
        }

        if (!\is_array($callable)) {
            return sprintf('Invalid type for controller given, expected string, array or object, got "%s".', \gettype($callable));
        }

        if (!isset($callable[0]) || !isset($callable[1]) || 2 !== \count($callable)) {
            return 'Invalid array callable, expected [controller, method].';
        }

        list($controller, $method) = $callable;

        if (\is_string($controller) && !class_exists($controller)) {
            return sprintf('Class "%s" does not exist.', $controller);
        }

        $className = \is_object($controller) ? \get_class($controller) : $controller;

        if (method_exists($controller, $method)) {
            return sprintf('Method "%s" on class "%s" should be public and non-abstract.', $method, $className);
        }

        $collection = $this->getClassMethodsWithoutMagicMethods($controller);

        $alternatives = [];

        foreach ($collection as $item) {
            $lev = levenshtein($method, $item);

            if ($lev <= \strlen($method) / 3 || false !== strpos($item, $method)) {
                $alternatives[] = $item;
            }
        }

        asort($alternatives);

        $message = sprintf('Expected method "%s" on class "%s"', $method, $className);

        if (\count($alternatives) > 0) {
            $message .= sprintf(', did you mean "%s"?', implode('", "', $alternatives));
        } else {
            $message .= sprintf('. Available methods: "%s".', implode('", "', $collection));
        }

        return $message;
    }

    private function getClassMethodsWithoutMagicMethods($classOrObject)
    {
        $methods = get_class_methods($classOrObject);

        return array_filter($methods, function (string $method) {
            return 0 !== strncmp($method, '__', 2);
        });
    }

}