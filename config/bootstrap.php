<?php
declare(strict_types=1);

use App\Application;
use Core\Config\ConfigInterface;
use Core\Container\StaticContainer;
use Core\Http\Router\RouterInterface;
use Spatie\Ignition\Ignition;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

require dirname(__DIR__) . '/config/paths.php';
require VENDOR_DIR . '/autoload.php';

Ignition::make()->register();

ini_set('date.timezone', $_ENV['TZ']);

/** @var StaticContainer $container */
$container = require CONFIG_DIR . '/container.php';

/** @var ConfigInterface $config */
$config = require CONFIG_DIR . '/configuration.php';

require CONFIG_DIR . '/helpers.php';

/** @var RouterInterface $router */
$router = require CONFIG_DIR . '/routes.php';

return new Application($container, $router);
