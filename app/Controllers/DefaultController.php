<?php
namespace App\Controllers;

use Mini\Router\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * @Route("/abc/{name}", name="abc", methods="GET", middleware="demo")
     */
    public function index(Request $request)
    {
        //var_dump($name);
        //return $this->redirect("/demo");
        return new Response("hello");
    }

    /**
     * @Route("/demo", group="web")
     */
    public function demo()
    {
        return new JsonResponse(["msg" => "demo"]);
    }

    /**
     * @Route("/view")
     */
    public function view() 
    {
        return $this->render("index", ["msg" => "Welcome!"]);
    }
}
