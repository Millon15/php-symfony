<?php
declare(strict_types=1);

include_once __DIR__ . '/Elem.php';
include_once __DIR__ . '/MyException.php';
include_once __DIR__ . '/TemplateEngine.php';

use d02\ex04\Elem;
use d02\ex04\MyException;
use d02\ex04\TemplateEngine;

$body = new Elem('body');

try {
    $body->pushElement(new Elem('', 'Lorem ipsum'));
} catch (MyException $e) {
    echo $e->getMessage() . PHP_EOL;
}

try {
    $body->pushElement(new Elem('brt', 'Lorem ipsum'));
} catch (MyException $e) {
    echo $e->getMessage() . PHP_EOL;
}

try {
    $body->pushElement(new Elem('32552532253', 'Lorem ipsum'));
} catch (MyException $e) {
    echo $e->getMessage() . PHP_EOL;
}

try {
    $body->pushElement(new Elem('li', 'Lorem ipsum', ['style' => 'background: black;', '']));
} catch (MyException $e) {
    echo $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;

try {
    $elem = new Elem('html', 'test1');
    $div = new Elem('div', 'test');
    $table = new Elem('table');

    $elem->pushElement($div);
    $elem->pushElement($div);
    $elem->pushElement($div);
    $elem->pushElement(new Elem('p', 'That all, folks!'));

    $div->pushElement(new Elem('p', 'Lorem ipsum'));
    $div->pushElement(new Elem('p', 'Lorem ipsum'));
    $div->pushElement($table);
    $div->pushElement(new Elem('br'));
    $div->pushElement(new Elem('br'));

    $table->pushElement(new Elem(
        'tr',
        'That\'s <xmp><tr></xmp>, bitch',
        ['style' => 'background: black;', 'horse' => 'true']
    ));

    echo $elem->getHTML();

    (new TemplateEngine($elem))->createFile('render.html');
} catch (MyException $e) {
    echo PHP_EOL . PHP_EOL . 'Something went wrong: ' . $e->getMessage() . PHP_EOL;
}
