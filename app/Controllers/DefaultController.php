<?php
namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController {

    /**
     * @Route("/abc/{name}", methods="GET")
     */
    public function index(Request $request, $name) {
        var_dump($name);
        return new Response("hello");
    }
}