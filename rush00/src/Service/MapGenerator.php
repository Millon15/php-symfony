<?php
declare(strict_types=1);

namespace App\Service;

class MapGenerator
{
    //parameters - moviemons
    //return map
    //center - waste
    //keys - random

    /**
     * @param $moviemons
     *
     * @return array
     * @throws \Exception
     */
    public function getMap($moviemons): array
    {
        $map = [];
        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 5; $j++) {
                if ($i !== 2 || $j !== 2) {
                    $map["$i$j"] = ['open' => false, 'moviemon' => null, 'position' => false];
                } else {
                    $map["$i$j"] = ['open' => true, 'moviemon' => null, 'position' => true];
                }
            }
        }

        for ($i = 0; $i < 10; $i++) {
            $x = \random_int(0, 4);
            $y = \random_int(0, 4);

            if ("$x$y" !== '22' && $map["$x$y"]['moviemon'] === null) {
                $map["$x$y"]['moviemon'] = $moviemons[$i];
            } else {
                --$i;
            }
        }

        return $map;
    }
}
