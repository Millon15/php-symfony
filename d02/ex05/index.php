<?php
declare(strict_types=1);

include_once __DIR__ . '/Elem.php';
include_once __DIR__ . '/MyException.php';
include_once __DIR__ . '/TemplateEngine.php';

use d02\ex05\Elem;
use d02\ex05\MyException;
use d02\ex05\TemplateEngine;

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
    $table = new Elem('table', '', ['style' => 'width: 100px; height: 100px;']);

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
        'td',
        'That\'s <xmp><tr></xmp>, bitch',
        ['style' => 'background: black; color: white;', 'horse' => 'true']
    ));

    if ($elem->validPage()) {
        echo $elem->getHTML();

        (new TemplateEngine($elem))->createFile('render.html');
    } else {
        echo "Couldn't createFile because of invalid HTML inside the Element object" . PHP_EOL;
    }
} catch (MyException $e) {
    echo PHP_EOL . PHP_EOL . 'Something went wrong: ' . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;

try {
    $html = new Elem('html');

    $head = new Elem('head');
    $title = new Elem('title', 'huitle');
    $meta = new Elem('meta', '', ['charset' => 'utf-8']);

    $body = new Elem('body', 'test1');
    $div = new Elem('div', 'testik');
    $table = new Elem('table', '', ['style' => 'width: 100px; height: 100px;']);


    $html->pushElement($head);
    $html->pushElement($body);

    $head->pushElement($title);
    $head->pushElement($meta);

    $body->pushElement($div);
    $body->pushElement(new Elem('p', 'That all, folks!'));

    $div->pushElement(new Elem('p', 'Lorem ipsum'));
    $div->pushElement(new Elem('p', 'Lorem ipsum'));
    $div->pushElement($table);
    $div->pushElement(new Elem('br'));
    $div->pushElement(new Elem('br'));

    $table->pushElement(new Elem(
        'td',
        'That\'s <xmp><tr></xmp>, bitch',
        ['style' => 'background: black; color: white;', 'horse' => 'true']
    ));

    (new TemplateEngine($html))->createFile('render.html');

    if ($html->validPage()) {
        echo $html->getHTML();
        echo PHP_EOL . PHP_EOL . 'Yeah, page is valid!' . PHP_EOL;
        (new TemplateEngine($html))->createFile('render.html');
    }
} catch (MyException $e) {
    echo PHP_EOL . PHP_EOL . 'Something went wrong: ' . $e->getMessage() . PHP_EOL;
}
