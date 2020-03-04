<?php

/**
 * Converts passed array of arrays to a hash where keys are ages and values are names.
 *
 * @param array $arrays
 *
 * @return array
 */
function array2hash(array $arrays): array
{
    $hash = [];
    foreach ($arrays as $array) {
        if (isset($array[0], $array[1])) {
            $hash[$array[1]] = $array[0];
        }
    }

    return $hash;
}
