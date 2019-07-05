<?php
namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Mini\Router\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller {

    /**
     * @Route("/abc/{name}", name="abc", methods="GET")
     */
    public function index(Request $request) {
        //var_dump($name);
        //return $this->redirect("demo");
        return new Response("hello");
    }

    /**
     * @Route("/demo")
     */
    public function demo() 
    {
        return new JsonResponse(["msg" => "demo"]);
    }
}