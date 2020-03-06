<?php
declare(strict_types=1);

include_once __DIR__ . '/Elem.php';
include_once __DIR__ . '/TemplateEngine.php';

use d02\ex03\Elem;
use d02\ex03\TemplateEngine;

$elem = new Elem('html', 'test1');
$body = new Elem('div', 'test');
$body->pushElement(new Elem('p', 'Lorem ipsum'));
$body->pushElement(new Elem('p', 'Lorem ipsum'));
$elem->pushElement($body);
$elem->pushElement($body);
$elem->pushElement(new Elem('p', 'Lorem ipsum'));
$div->pushElement(new Elem('br'));

echo $elem->getHTML();

(new TemplateEngine($elem))->createFile('render.html');
