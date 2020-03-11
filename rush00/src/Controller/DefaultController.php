<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\PlayerType;
use App\Entity\Player;
use App\Service\MoviemonGenerator;
use App\Service\MapGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param KernelInterface $kernel
     * @param Request         $request
     *
     * @return Response
     */
    public function index(KernelInterface $kernel, Request $request): Response
    {
        $session = $this->get('session');
        $playerName = $session->get('player_name');
        $player = new Player();
        if ($playerName) {
            $player->setName($playerName);
        }
        $form = $this->createForm(PlayerType::class, $player);

        $finder = new Finder();
        $finder->files()->in($kernel->getProjectDir() . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Saves');

        if ($request->get('gameOver')) {
            $this->addFlash('gameover', 'Game Over! Please choose a saved game or create new player and enjoy a new game!');
        }

        if ($request->get('success')) {
            $this->addFlash('success', 'Congratulations! You can create new player and enjoy a new game!');
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'player_name' => $playerName,
            'saves' => $finder
        ]);
    }

    /**
     * @Route("/create-player", name="create_player", methods={"POST"})
     * @param Request           $request
     * @param MoviemonGenerator $moviemonGenerator
     * @param MapGenerator      $mapGenerator
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function createPlayer(Request $request, MoviemonGenerator $moviemonGenerator, MapGenerator $mapGenerator): RedirectResponse
    {
        $player = new Player();
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        $session = $this->get('session');
        if ($session->get('player_name') === $form->getData()->getName()) {
            return $this->redirectToRoute('map_index');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $player = $form->getData();
            $moviemons = $moviemonGenerator->get();
            $session->set('player_name', $player->getName());
            $session->set('player', $player);
            $session->set('moviemons', $moviemons);
            $session->set('map', $mapGenerator->getMap($moviemons));
        } else {
            return $this->redirectToRoute('homepage');
        }

        return $this->redirectToRoute('map_index');
    }

    /**
     * @Route("/load-game/{playerName}", name="load_game")
     *
     * @param $playerName
     * @param KernelInterface $kernel
     * @return RedirectResponse
     */
    public function loadGame($playerName, KernelInterface $kernel): RedirectResponse
    {
        $session = $this->get('session');

        $finder = new Finder();
        $finder->files()
            ->in($kernel->getProjectDir() . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Saves')
            ->name($playerName);

        if (!$finder->hasResults()) {
            return $this->redirectToRoute('homepage');
        }

        $iterator = $finder->getIterator();
        $iterator->rewind();
        $save = \json_decode($iterator->current()->getContents(), true);

        $player = new Player();
        $player
            ->setName($save['player']['name'])
            ->setLevel($save['player']['level'])
            ->setHealth($save['player']['health'])
            ->setAttack($save['player']['attack']);

        array_walk($save['map'], function (&$item) {
            if (!empty($item['moviemon'])) {
                $item['moviemon'] = json_decode(json_encode($item['moviemon']));
            }
        });

        array_walk($save['moviemons'], function (&$item) {
            $item = json_decode(json_encode($item));
        });

        $session->set('player_name', $playerName);
        $session->set('player', $player);
        $session->set('moviemons', $save['moviemons']);
        $session->set('map', $save['map']);

        return $this->redirectToRoute('map_index');
    }
}
