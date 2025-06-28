<?php
declare(strict_types=1);

namespace Core\View;

use Core\Http\Exceptions\ServerErrors\InternalErrorException;

final readonly class ViewRenderer implements ViewRendererInterface
{
    /**
     * @inheritDoc
     */
    public function render(string $template, array $data = []): string
    {
        $templatePath = VIEWS_DIR . DS . "$template.php";

        if (!file_exists($templatePath)) {
            throw new InternalErrorException("Template file does not exist: $templatePath");
        }

        ob_start();
        extract($data);
        require $templatePath;

        return (string)ob_get_clean();
    }
}
