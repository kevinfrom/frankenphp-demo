<?php
declare(strict_types=1);

$replaceRoot = function (string $path): string {
    if (!str_starts_with($path, ROOT_DIR)) {
        return $path;
    }

    return '~' . substr($path, strlen(ROOT_DIR));
};

/**
 * @var Throwable $exception
 * @var \Core\View\ViewRendererInterface $this
 */

$title = vsprintf('%s: %s (%s at line %d)', [
    $exception::class,
    $exception->getMessage(),
    $exception->getFile(),
    $exception->getLine(),
]);

/**
 * @var array{
 *     file: string,
 *     line: int,
 *     function: string,
 * } $stack
 */
$stack = [];

foreach ($exception->getTrace() as $item) {
    $function = $item['function'];
    if (isset($item['class'])) {
        $function = $item['class'] . '::' . $function;
    }

    $stack[] = [
        'file' => $replaceRoot($item['file']),
        'line' => $item['line'],
        'function' => $function,
    ];
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="color-scheme" content="light dark">
    <title><?= \Core\sanitize($title) ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-200 dark:bg-gray-900">
<main>
    <div class="mx-auto px-4 sm:max-w-[640px] md:max-w-[768px] lg:max-w-[1024px] xl:max-w-[1280px] 2xl:max-w-[1536px]">
        <div class="mt-12 p-6 rounded shadow bg-gray-50 dark:bg-gray-800">
            <div class="flex justify-between">
                <p class="font-semibold"><?= \Core\sanitize($exception::class) ?></p>
                <p class="opacity-50">php <?= PHP_VERSION ?></p>
            </div>

            <hr class="my-2 text-gray-300 dark:text-gray-700">
            <p><?= \Core\sanitize($exception->getMessage()) ?></p>
            <p class="opacity-50"><?= sprintf('Thrown at %s:%d', $replaceRoot($exception->getFile()), $exception->getLine()) ?></p>
        </div>

        <div class="mt-12 rounded shadow bg-gray-50 dark:bg-gray-800">
            <?php foreach ($stack as $item) { ?>
                <div class="p-6 border-b last:border-b-0 border-gray-300 dark:border-gray-700">
                    <p class="font-medium"><?= \Core\sanitize($item['function']) ?></p>
                    <p class="opacity-50"><?= sprintf('Called at %s:%d', $item['file'], $item['line']) ?></p>
                </div>
            <?php } ?>
        </div>

        <div class="my-12 text-center">
            <a href="https://github.com/kevinfrom/frankenphp-demo" target="_blank" rel="noopener">
                github
            </a>
        </div>
    </div>
</main>
</body>
</html>
