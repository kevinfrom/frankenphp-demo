<?php
declare(strict_types=1);

/**
 * @var string $title
 * @var string $content
 * @var \Core\View\ViewRendererInterface $this
 */

$title ??= '';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= \Core\sanitize($title) ?></title>
    <link rel="stylesheet" href="/bootstrap.min.css">
</head>
<body class="min-vh-100 d-flex flex-column">
<?= $this->render('Components/header') ?>

<main class="mt-5 flex-grow-1">
    <div class="container d-flex flex-column">
        <?= $content ?>
    </div>
</main>

<?= $this->render('Components/footer') ?>
</body>
</html>
