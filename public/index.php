<?php

require __DIR__."/../vendor/autoload.php";

$app = new Mini\Application(__DIR__."/../");

$app->bind(Mini\Contract\ConfigInterface::class, Mini\Config\Config::class);

$app->start();