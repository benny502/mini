<?php

require __DIR__."/../vendor/autoload.php";

$app = new Mini\Application(__DIR__."/../");

$app->bind(Mini\Contracts\ConfigInterface::class, Mini\Core\Config::class);

$app->start();