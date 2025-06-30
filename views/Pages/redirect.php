<?php

declare(strict_types=1);

/**
 * @var \Core\View\ViewRendererInterface $this
 */
?>

<h1>Redirect</h1>
<p>
    Click the following redirect to perform a POST request to
    <code>/redirect</code>,
    which will redirect you to
    <code>/about</code>.
</p>

<form method="post" action="/redirect">
    <button type="submit" class="btn btn-primary">Redirect me!</button>
</form>
