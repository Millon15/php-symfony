<?php
declare(strict_types=1);

include_once __DIR__ . '/TemplateEngine.php';

use d02\ex00\TemplateEngine;

(new TemplateEngine())->createFile('render.html', 'book_description.html', [
    'nom' => 'NAME',
    'auteur' => 'AUTHOR',
    'description' => 'DESC',
    'prix' => 'PRICE',
]);
