<?php
namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController {

    public function index(Request $request) {
        var_dump($request->query->all());
        return new Response("hello");
    }
}