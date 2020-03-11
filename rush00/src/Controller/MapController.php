<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Player;
use App\Service\MoviemonGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map_index")
     *
     * @return RedirectResponse|Response
     */
    public function indexAction()
    {
        $session = $this->get('session');
        $playerName = $session->get('player_name');
        $map = $session->get('map');
        if (empty($playerName)) {
            return $this->redirectToRoute('homepage');
        }

        $defeated = 0;
        foreach ($map as $square) {
            if ($square["moviemon"] && $square["moviemon"]->defeated) {
                    $defeated++;
            }
        }
        
        if ($defeated == 10) {
            return $this->redirectToRoute('homepage', ['success' => true]);
        }

        return $this->render('map/index.html.twig', [
            'player_name' => $playerName,
            'map' => $map
        ]);
    }

    /**
     * @Route("/map/movie-dex", name="map_movie_dex")
     *
     * @param MoviemonGenerator $generator
     * @return RedirectResponse|Response
     */
    public function movieDex(MoviemonGenerator $generator)
    {
        $session = $this->get('session');
        $playerName = $session->get('player_name');
        if (empty($playerName)) {
            return $this->redirectToRoute('homepage');
        }

        $moviemons = $session->get('moviemons');

        return $this->render('map/movie_dex.html.twig', [
            'player_name' => $playerName,
            'movie_dex' => $moviemons
        ]);
    }

    /**
     * @Route("/map/save-game", name="map_save_game")
     *
     * @param KernelInterface $kernel
     * @return RedirectResponse
     */
    public function saveGame(KernelInterface $kernel): RedirectResponse
    {
        $session = $this->get('session');
        $playerName = $session->get('player_name');
        $player = $session->get('player');
        $moviemons = $session->get('moviemons');
        $map = $session->get('map');

        $savedInfo = [
            'map' => $map,
            'player' => $player->__toArray(),
            'moviemons' => $moviemons,
        ];

        $filesystem = new Filesystem();
        $filesystem->dumpFile(
            $kernel->getProjectDir() . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Saves' . DIRECTORY_SEPARATOR . $playerName,
            \json_encode($savedInfo)
        );

        return $this->redirectToRoute('map_index');
    }


    /**
     * @Route("/map/{mapId}", name="map_move")
     *
     * @param $mapId
     * @return RedirectResponse
     */
    public function move($mapId): RedirectResponse
    {
        $session = $this->get('session');
        $playerName = $session->get('player_name');
        if (empty($playerName)) {
            return $this->redirectToRoute('homepage');
        }
        $map = $session->get('map');

        if (!isset($map[$mapId])) {
            return $this->redirectToRoute('map_index');
        }

        $mapEntity = $map[$mapId];
        $map[$mapId]['open'] = true;
        $playerMapPosition = current(\array_keys(\array_filter($map, static function ($entity) {
            return $entity['position'];
        })));
        $map[$playerMapPosition]['position'] = false;
        $map[$mapId]['position'] = true;
        $session->set('map', $map);

        if (!empty($mapEntity['moviemon'])) {
            if ($mapEntity['moviemon']->defeated) {
                return $this->redirectToRoute('map_index');
            }
            return $this->redirectToRoute('map_fight', ['mapId' => $mapId]);
        }

        return $this->redirectToRoute('map_index');
    }

    /**
     * @Route("/map/{mapId}/fight", name="map_fight")
     *
     * @param $mapId
     * @return Response
     */
    public function fight($mapId): Response
    {
        $session = $this->get('session');
        /** @var Player $player */
        $player = $session->get('player');
        $map = $session->get('map');
        $moviemon = $map[$mapId]['moviemon'];

        return $this->render('map/fight.html.twig', [
            'map_id' => $mapId,
            'player_name' => $player->getName(),
            'player' => $player,
            'moviemon' => $moviemon
        ]);
    }

    /**
     * @Route("/map/{mapId}/fight/attack", name="map_fight_attack")
     *
     * @param $mapId
     * @return Response
     */
    public function attack($mapId): Response
    {
        $session = $this->get('session');
        /** @var Player $player */
        $player = $session->get('player');
        if (!$player) {
            return $this->redirectToRoute('homepage', ['gameOver' => true]);
        }
        $map = $session->get('map');
        $moviemon = $map[$mapId]['moviemon'];

        // attack moviemon by player
        $moviemon->health -= $player->getAttack();
        if ($moviemon->health <= 0) {
            $moviemon->health = 0;
            $moviemon->defeated = true;
            $map[$mapId]['moviemon'] = $moviemon;
            $session->set('map', $map);
            $player->levelUp();
            $session->set('player', $player);

            $moviemons = $session->get('moviemons');

            array_walk($moviemons, function (&$item) use ($moviemon) {
                if ($item->imdbID === $moviemon->imdbID) {
                    $item->defeated = true;
                }
            });

            return $this->redirectToRoute('map_index');
        }
        // attack player by moviemon
        $player->setHealth($player->getHealth() - $moviemon->attack);
        if ($player->getHealth() <= 0) {
            $player->setHealth(0);
            $session->clear();

            return $this->redirectToRoute('homepage', ['gameOver' => true]);
        }

        return $this->render('map/fight.html.twig', [
            'map_id' => $mapId,
            'player_name' => $player->getName(),
            'player' => $player,
            'moviemon' => $moviemon
        ]);
    }
}
