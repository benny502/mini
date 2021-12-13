<?php
namespace App\Middleware;

use Mini\Contract\MiddlewareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoMiddleware implements MiddlewareInterface{

    public function handle(Request $request, $next) : Response {
        //echo $request->__toString();
        return $next($request);
        //return new Response("stop");
    }

}