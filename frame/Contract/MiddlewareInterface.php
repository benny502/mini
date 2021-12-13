<?php
namespace Mini\Contract;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

interface MiddlewareInterface {
    public function handle(Request $request,MiddlewareInterface $next) : HttpFoundationResponse;
} 