<?php
declare(strict_types=1);

/**
 * @var \Core\View\ViewRendererInterface $this
 */
?>

<h1><?= \Core\sanitize(\Core\config()->get('App.name')) ?></h1>
<p>Hello World!</p>

<p>This is the index page of the application.</p>
<a href="/about">Read more!</a>
