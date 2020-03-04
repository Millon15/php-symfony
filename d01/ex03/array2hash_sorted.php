<?php

/**
 * Converts passed array of arrays to the array of [names => ages], sorted by reverse alphabetical order array.
 *
 * @param array $arrays
 *
 * @return array
 */
function array2hash_sorted(array $arrays): array
{
    $hash = [];

    // By task:
    // usort($arrays, static function ($item1, $item2) {
    //     return -($item1[0] <=> $item2[0]);
    // });

    // By example:
    usort($arrays, static function ($item1, $item2) {
        return ($item1[1] <=> $item2[1]) ?: -($item1[0] <=> $item2[0]);
    });

    foreach ($arrays as $array) {
        if (isset($array[0], $array[1])) {
            $hash[$array[0]] = $array[1];
        }
    }

    return $hash;
}
