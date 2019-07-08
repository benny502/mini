<?php
namespace Mini\Contract;
use Mini\Contract\ApplicationAware;
use Mini\Contract\ApplicationAwareTrait;

abstract class Configuration implements ApplicationAware
{
    use ApplicationAwareTrait;

    abstract public function register();
}