<?php
declare(strict_types=1);

/**
 * @var string $content
 * @var string $error
 * @var int $code
 * @var Throwable $exception
 * @var \Core\View\ViewRendererInterface $this
 */

$title = match ($code) {
    400 => 'Bad request',
    401 => 'You are not signed in',
    403 => 'You are not allowed to access this resource',
    404 => 'Resource not found',
    503 => 'Service unavailable',
    default => 'An error occurred',
};

// @TODO: Build a proper dev error page
/*if (\Core\config()->get('debug')) {
    $this->render('dev_error', [
        'exception' => $exception,
    ]);
    return;
}*/

echo $this->render('Layouts/default', [
    'title' => $title,
    'content' => <<<HTML
        <div class="mt-5 d-flex justify-content-center align-items-center text-center">$content</div>
    HTML,
]);
