<?php
namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Mini\Router\Route;

class DefaultController {

    /**
     * @Route("/abc/{name}", name="abc", methods="GET")
     */
    public function index(Request $request) {
        //var_dump($name);
        return new Response("hello");
    }
}