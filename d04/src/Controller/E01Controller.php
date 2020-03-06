<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;

/**
 * @Route("/e01", name="e01_")
 */
class E01Controller extends AbstractController
{
    /**
     * @Route("/{name}", name="index", methods={"GET"})
     * @param string $name
     *
     * @return Response
     */
    public function index(string $name = 'index'): Response
    {
        try {
            return $this->render("e01/$name.html.twig", [
                'controller_name' => 'E01Controller',
            ]);
        } catch(LoaderError $e) {
            return $this->render("e01/index.html.twig", [
                'controller_name' => 'E01Controller',
            ]);
        }
    }
}
