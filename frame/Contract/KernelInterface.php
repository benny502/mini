<?php

namespace Mini\Contract;

use Symfony\Component\HttpFoundation\Request;

interface KernelInterface 
{
    public function handle(Request $request);
}