<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/e02", name="e02_")
 */
class E02Controller extends AbstractController
{
    /** @var Filesystem */
    private $filesystem;

    /** @var string */
    private $filename;

    public function __construct(Filesystem $filesystem, ParameterBagInterface $params)
    {
        $this->filesystem = $filesystem;
        $this->filename = "../{$params->get('filename')}";
    }

    /**
     * @Route("/", name="form", methods={"GET", "POST"})
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function form(Request $request): Response
    {
        $form = $this->createFormBuilder(null, [
            'action' => $this->generateUrl('e02_form'),
            'method' => 'POST',
        ])
            ->add('message', TextareaType::class, [
                'required' => true,
                'trim' => true,
            ])
            ->add('timestamp', ChoiceType::class, [
                'required' => true,
                'choices' => ['Yes' => true, 'No' => false],
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        $formContent = $request->request->get('form', false);
        if ($form->isSubmitted() && $formContent && $formContent['message'] ?? false) {
            $timestamp = (bool)$formContent['timestamp'] ? (new \DateTime())->format(\DateTime::RFC7231) : '';
            $this->filesystem->appendToFile($this->filename, $formContent['message']
                                                             . ($timestamp ? PHP_EOL : '')
                                                             . $timestamp
                                                             . PHP_EOL);
        }

        return $this->render('e02/index.html.twig', [
            'controller_name' => 'E02Controller',
            'form' => $form->createView(),
            'last_message' => $formContent['message'] ?? null,
        ]);
    }
}
