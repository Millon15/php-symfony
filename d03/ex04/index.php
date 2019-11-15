<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

use Weather\Weather;

echo Weather::getWeather($argv[1] ?? 'Kiev');
