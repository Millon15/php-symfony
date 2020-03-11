<?php

namespace App\Service;

class MoviemonGenerator
{
    /** @var string */
    private $_apiKey;

    /** @var string filename */
    private $_imdbMoviemons;

    /** @var ApiProcessor */
    private $_apiProcessor;


    /**
     * MoviemonGenerator constructor.
     *
     * @param $apiKey
     * @param $imdbMoviemons
     * @param ApiProcessor $apiProcessor
     */
    public function __construct($apiKey, $imdbMoviemons, ApiProcessor $apiProcessor)
    {
        $this->_apiKey = $apiKey;
        $this->_imdbMoviemons = $imdbMoviemons;
        $this->_apiProcessor = $apiProcessor;
    }

    /**
     * @return array
     */
    public function get()
    {
        $generalIds = explode(',', file_get_contents($this->_imdbMoviemons));
        $bossIds = array_splice($generalIds, 0, 4);

        $randomGeneralKeys = array_rand($generalIds, 9);
        $randomBossKey = array_rand($bossIds, 1);

        $resultingMoviemonsIds = array_intersect_key($generalIds, array_flip($randomGeneralKeys));
        $resultingMoviemonsIds[] = $bossIds[$randomBossKey];


        $moviemons = [];
        foreach ($resultingMoviemonsIds as $id) {
            $moviemons[] = $this->_processMoviemon($this->_apiProcessor->call(['apikey' => $this->_apiKey, 'i' => $id]));
        }

        usort($moviemons, function($a, $b) {
            return ($a->level < $b->level) ? -1 : 1;
        });

        return $moviemons;
    }

    /**
     * @param $moviemon
     * @return mixed
     */
    private function _processMoviemon($moviemon)
    {
        $level = intval(preg_replace('/\./', '', sprintf('%.1f', floatval($moviemon->imdbRating) - 8.0)));
        if ($level <= 0) {
            $level = 1;
        }

        $moviemon->level = $level;
        $moviemon->defeated = false;

        $health = 5;
        if ($level < 5) {
            $health = rand(1, 5);
        } elseif ($level >= 5 && $level <= 9) {
            $health = rand(5, 10);
        } elseif ($level > 9) {
            $health = rand(10, 15);
        }
        $moviemon->health = $health;

        $attack = 1;
        if ($level > 9) {
            $attack = 2;
        }
        $moviemon->attack = $attack;

        return $moviemon;
    }
}