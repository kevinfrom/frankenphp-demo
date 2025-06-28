<?php
declare(strict_types=1);

use function Core\config;

config()->set([
    'debug' => true,
    'App' => [
        'name' => 'FrankenPHP demo',
    ],
    'Error' => [
        'templates' => [
            '400' => 'Errors' . DS . '400',
            '500' => 'Errors' . DS . '500',
            'dev' => 'Errors' . DS . 'dev',
        ],
    ],
]);
