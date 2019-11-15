<?php
declare(strict_types=1);

namespace App\Controller;

use Exception;
use RuntimeException;
use App\Service\FileWriter;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @param FileWriter $userFile
     * @param Request    $request
     *
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function createUser(FileWriter $userFile, Request $request): Response
    {
        $request_content = json_decode($request->getContent(), true);
        $userName = $request_content['userName'];
        if (!$userName) {
            throw new RuntimeException("Can't retreive user name!");
        }

        $client = HttpClient::create();
        $userId = '';
        $movies = [];
        $const = 1000000;
        for ($i = 0; $i < 10; ++$i) {
            do {
                $page = (string)random_int(1, $const - 1);
                $response = json_decode($client
                    ->request('GET', "http://www.omdbapi.com/?i=tt00{$page}&type=movie&apikey=3fe767b6")
                    ->getContent(), true);
                try {
                    $client->request('GET', $response['Poster'] ?? 'N/A');
                } catch (\Throwable $e) {
                    continue;
                }
            } while ($response['Response'] !== 'True'
                     || !isset($response['Title'], $response['Poster'])
                     || $response['Poster'] === 'N/A');

            $movie = [
                'id' => (int)$page,
                // 'poster' => (($response['Poster'] ?? 'N/A') === 'N/A')
                //     ? 'https://www.genesis.com/content/dam/genesis/us/images/chromecompare/not-available-icon-lrg.svg'
                //     : $response['Poster'],
                'poster' => $response['Poster'],
                'name' => $response['Title'],
                'plot' => $response['Plot'] ?? 'N/A',
                'year' => $response['Year'] ?? 'N/A',
                'rated' => $response['Rated'] ?? 'N/A',
                'released' => $response['Released'] ?? 'N/A',
                'runtime' => $response['Runtime'] ?? 'N/A',
                'genre' => $response['Genre'] ?? 'N/A',
                'director' => $response['Director'] ?? 'N/A',
                'writer' => $response['Writer'] ?? 'N/A',
                'actors' => $response['Actors'] ?? 'N/A',
                'language' => $response['Language'] ?? 'N/A',
                'country' => $response['Country'] ?? 'N/A',
                'isDefeated' => false,
            ];

            $movies[] = $movie;
            $userId .= $page % 10;
        }

        $createdAt = date('d-m-Y H:i:s');
        $level = 1;
        $user = [
            'userId' => $userId,
            'userName' => $userName,
            'createdAt' => $createdAt,
            'level' => $level,
            'movies' => $movies,
        ];

        $userFile->write($userId, $user);

        return new Response($userId);
    }

    /**
     * @param FileWriter $userFile
     *
     * @return Response
     */
    public function savedGames(FileWriter $userFile): Response
    {
        $users = $userFile->read();

        $usersToReturn = [];
        foreach ($users as $user) {
            $usersToReturn[] = [
                'userName' => $user['userName'],
                'userId' => $user['userId'],
                'createdAt' => $user['createdAt'],
            ];
        }

        return $this->json($usersToReturn);
    }

    /**
     * @param FileWriter $userFile
     * @param Request    $request
     *
     * @return Response
     */
    public function userProgress(FileWriter $userFile, Request $request): Response
    {
        $userId = json_decode($request->getContent(), true)['userId']
                  ?? $request->query->get('userId');
        if (!$userId) {
            throw new RuntimeException("Can't retreive user id!");
        }
        $user = $userFile->read()[$userId] ?? null;
        if (!$user) {
            throw new RuntimeException("Can't retreive user info by user id: " . $userId);
        }

        return $this->json(['movies' => $user['movies']]);
    }

    /**
     * @param FileWriter $userFile
     * @param Request    $request
     *
     * @return Response
     * @throws Exception
     */
    public function fightCondinions(FileWriter $userFile, Request $request): Response
    {
        $userId = json_decode($request->getContent(), true)['userId']
                  ?? $request->query->get('userId');
        if (!$userId) {
            throw new RuntimeException("Can't retreive user id!");
        }
        $user = $userFile->read()[$userId] ?? null;
        if (!$user) {
            throw new RuntimeException("Can't retreive user info by user id: " . $userId);
        }

        do {
            $movie = $user['movies'][random_int(0, 9)];
        } while ($movie['isDefeated'] === true);

        return $this->json([
            'userInfo' => [
                'hp' => $user['level'] * 3,
                'damage' => $user['level'] * 2,
            ],
            'enemy' => [
                'id' => $movie['id'],
                'poster' => $movie['poster'],
                'hp' => $user['level'] * 4,
                'damage' => $user['level'] * 1,
            ],
        ]);
    }

    /**
     * @param FileWriter $userFile
     * @param Request    $request
     *
     * @return Response
     */
    public function gameOver(FileWriter $userFile, Request $request): Response
    {
        $j = json_decode($request->getContent(), true);
        if (!$j['userId']) {
            throw new RuntimeException("Can't retreive user id!");
        }
        if (!$j['movieId']) {
            throw new RuntimeException("Can't retreive movie id!");
        }

        $user = $userFile->read()[$j['userId']] ?? null;
        if (!$user) {
            throw new RuntimeException("Can't retreive user info by user id: " . $j['userId']);
        }

        foreach ($user['movies'] as &$mov) {
            if ($mov['id'] == $j['movieId']) {
                $movie = &$mov;
            }
        }
        unset($mov);
        if (!isset($movie)) {
            throw new RuntimeException("Can't retreive movie info by movie id: " . $j['movieId']);
        }

        $user['level'] += 1;
        $movie['isDefeated'] = true;

        $userFile->update($j['userId'], $user);

        return new Response();
    }
}
