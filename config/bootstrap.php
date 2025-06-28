<?php
declare(strict_types=1);

use App\Application;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

require dirname(__DIR__) . '/config/paths.php';
require VENDOR_DIR . '/autoload.php';

ini_set('date.timezone', $_ENV['TZ']);

return new Application();
