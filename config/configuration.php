<?php
declare(strict_types=1);

use Core\Config\Config;

$config = Config::getInstance();

$config->set([
    'debug' => true,
    'App' => [
        'name' => 'FrankenPHP demo',
    ],
]);
