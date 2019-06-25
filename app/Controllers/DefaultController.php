<?php
namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController {

    /**
     * @Route("/abc")
     */
    public function index(Request $request) {
        var_dump($request->query->all());
        return new Response("hello");
    }
}