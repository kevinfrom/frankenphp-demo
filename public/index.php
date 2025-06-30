<?php

declare(strict_types=1);

use App\Application;

/** @var Application $app */
$app = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app->run();
