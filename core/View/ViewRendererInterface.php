<?php

declare(strict_types=1);

namespace Core\View;

use Core\Http\Exceptions\ServerErrors\InternalErrorException;

interface ViewRendererInterface
{
    /**
     * Render a view file with the provided data.
     *
     *
     * @param string $template The template file to include, relative to the views directory.
     * @param array  $data Data to pass to the template.
     *
     * @return string
     * @throws InternalErrorException
     */
    public function render(string $template, array $data = []): string;
}
