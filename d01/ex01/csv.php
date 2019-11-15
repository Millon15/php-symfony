<?php

if (($handle = fopen('ex01.txt', 'rb')) !== false) {
    while (($data = fgetcsv($handle)) !== false) {
        foreach ($data as $datum) {
            echo $datum . PHP_EOL;
        }
    }
    fclose($handle);
}
