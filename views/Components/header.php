<?php

declare(strict_types=1);

/**
 * @var \Core\View\ViewRendererInterface $this
 */

/** @var array<string, string> $routes */
$routes = [
    '/'         => 'Home',
    '/about'    => 'About',
    '/redirect' => 'Redirect',
];

$routeIsActive = function (string $route) {
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === $route;
};
?>

<header class="border-b shadow-sm">
    <div class="container">
        <nav class="navbar">
            <a class="navbar-brand" href="/">
                <?= \Core\sanitize(\Core\config()->get('App.name')) ?>
            </a>
            <div class="navbar-expand">
                <ul class="navbar-nav">
                    <?php foreach ($routes as $route => $name) { ?>
                        <li class="navbar-item">
                            <a class="nav-link <?= $routeIsActive($route) ? 'active text-decoration-underline' : '' ?>"
                               href="<?= $route ?>">
                                <?= \Core\sanitize($name) ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
    </div>
</header>
