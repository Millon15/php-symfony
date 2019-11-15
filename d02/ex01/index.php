<?php
declare(strict_types=1);

include_once __DIR__ . '/TemplateEngine.php';
include_once __DIR__ . '/Text.php';

use d02\ex01\TemplateEngine;
use d02\ex01\Text;

$text = new Text([
    'STR1',
    'STR2',
]);
$text->addString('STR3');
$text->addString('STR4');

(new TemplateEngine())->createFile('render.html', $text);
