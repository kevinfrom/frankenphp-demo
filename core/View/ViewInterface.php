<?php
declare(strict_types=1);

namespace Core\View;

interface ViewInterface
{
    /**
     * Get content type of the view compatible with HTTP response header "Content-Type".
     *
     * @return string
     */
    public function getContentType(): string;

    /**
     * Get data to be passed to the template.
     *
     * @return array
     */
    public function getTemplateData(): array;

    /**
     * Set template data
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setTemplateData(string $key, mixed $value): void;

    /**
     * Render the view and return the content.
     *
     * @return string
     */
    public function render(): string;
}
