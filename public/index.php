<?php

require __DIR__."/../vendor/autoload.php";

$container = Mini\Core\Container::getInstance();

$container->bind(Mini\Contract\ConfigLoaderInterface::class, function() {
    return new Mini\Config\ConfigLoader(__DIR__."/../config/");
});

$app = $container->make(Mini\Application::class);

$app->start();