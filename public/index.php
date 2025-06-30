<?php

declare(strict_types=1);

ignore_user_abort(true);

use App\Application;
use Core\Container\ContainerException;

/** @var Application $app */
$app = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'bootstrap.php';

/**
 * @throws ReflectionException
 * @throws ContainerException
 */
$handler = static function () use ($app): void {
    echo $app->run();
};

// Short circuit if FrankenPHP extension is not loaded or is not running in FrankenPHP worker mode
if (
    !extension_loaded('frankenphp')
    || empty($_SERVER['FRANKENPHP_CONFIG'])
    || !str_contains($_SERVER['FRANKENPHP_CONFIG'], 'worker')
) {
    $handler();
    exit;
}

$maxRequests = (int)($_SERVER['MAX_REQUESTS'] ?? 0);
for ($nbRequests = 0; !$maxRequests || $nbRequests < $maxRequests; $nbRequests++) {
    $keepRunning = frankenphp_handle_request($handler);

    gc_collect_cycles();

    if (!$keepRunning) {
        break;
    }
}
