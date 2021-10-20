<?php
namespace App\Middleware;

use Mini\Contract\Configuration;

class MiddlewareConfig extends Configuration {
    public function register() {
        $this->app->bind("middleware.demo", DemoMiddleware::class);
    }
}