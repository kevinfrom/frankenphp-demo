<?php

declare(strict_types=1);

/**
 * @var \Core\View\ViewRenderer $this
 */

$githubRepoUrl = 'https://github.com/kevinfrom/frankenphp-demo';
$githubLogoUrl = '/github-logo.svg';
?>

<footer class="d-flex justify-content-center align-items-center column-gap-1 p-3 border-top text-muted">
    <span>&copy; <?= date('Y') ?></span>
    <span>&sdot;</span>
    <span>FrankenPHP demo</span>
    <span>&sdot;</span>
    <a href="<?= $githubRepoUrl ?>" class="underline" target="_blank" rel="noopener">
        <img src="<?= $githubLogoUrl ?>" alt="GitHub logo" height="16">
    </a>
</footer>
