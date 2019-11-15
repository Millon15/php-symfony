<?php

$a = 10;
$b = '10';
$c = 'ten';
$d = 10.0;

echo 'My first variables:' . PHP_EOL;
foreach (['a', 'b', 'c', 'd'] as $var) {
    echo $var . ' contains : ' . $$var . ' and has type : ' . gettype($$var) . PHP_EOL;
}
