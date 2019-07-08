<?php

namespace Mini\Contract;

use Symfony\Component\HttpFoundation\Request;

interface KernelInterface 
{
    const MASTER_REQUEST = 1;
    const SUB_REQUEST = 2;
    public function handle(Request $request, $type, $catch);
}