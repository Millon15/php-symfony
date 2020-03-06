<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/e03", name="e03_")
 */
class E03Controller extends AbstractController
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @Route("/", name="colors")
     */
    public function index()
    {
        return $this->render('e03/index.html.twig', [
            'controller_name' => 'E03Controller',
            'number_of_colors' => $this->params->get('e03.number_of_colors'),
        ]);
    }
}
