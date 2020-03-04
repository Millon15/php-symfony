<?php
/** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

include_once __DIR__ . '/TemplateEngine.php';
include_once __DIR__ . '/HotBeverage.php';
include_once __DIR__ . '/Tea.php';
include_once __DIR__ . '/Cofee.php';

use d02\ex02\Tea;
use d02\ex02\Cofee;
use d02\ex02\HotBeverage;
use d02\ex02\TemplateEngine;

(new TemplateEngine())->createFile(new Tea);
(new TemplateEngine())->createFile(new Cofee);
