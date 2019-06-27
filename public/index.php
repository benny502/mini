<?php

require __DIR__."/../vendor/autoload.php";

$app = new Mini\Application(__DIR__. "/../");

$app->singleton(Mini\Contract\KernelInterface::class, Mini\Core\Kernel::class);

$app->singleton(Mini\Contract\ConfigLoaderInterface::class, Mini\Config\ConfigLoader::class);

$kernel = $app->make(Mini\Contract\KernelInterface::class);

$response = $kernel->handle(
    Symfony\Component\HttpFoundation\Request::createFromGlobals()
);

$response->send();

$kernel->terminate($request, $response);
