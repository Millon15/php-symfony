<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/e00", name="hello_world_")
 */
class HelloWorldController
{
    /**
     * @Route("/firstpage", name="first_page", methods={"GET"})
     */
    public function helloWorld(): Response
    {
        return new Response('Hello world!');
    }
}
