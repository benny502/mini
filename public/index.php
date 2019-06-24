<?php

require __DIR__."/../vendor/autoload.php";

$app = new Mini\Application(__DIR__."/../");

$app->bind(Mini\Contracts\Config::class, Mini\Core\Config::class);

$app->start();