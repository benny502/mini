<?php
namespace Mini\Contract;

use Symfony\Component\HttpFoundation\Request;

interface MiddlewareInterface {
    public function handle(Request $request,MiddlewareInterface $next) : Request;
} 