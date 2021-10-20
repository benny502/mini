<?php
namespace App\Middleware;

use Mini\Contract\MiddlewareInterface;
use Symfony\Component\HttpFoundation\Request;

class DemoMiddleware implements MiddlewareInterface{

    public function handle(Request $request, $next) : Request {
        echo $request->__toString();
        return $request;
    }

}