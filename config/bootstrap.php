<?php

declare(strict_types=1);

use App\Application;
use function Core\container;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

require dirname(__DIR__) . '/config/paths.php';
require VENDOR_DIR . '/autoload.php';

return container()->get(Application::class);
