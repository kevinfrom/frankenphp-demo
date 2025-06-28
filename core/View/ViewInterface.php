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
    public function getData(): array;

    /**
     * Set template data
     *
     * @param string|array $key If key is a string, it will be used as the key for the value. If $key is an array, it will be merged with the existing data.
     * @param mixed $value If $key is a string, this will be the value associated with that key. If $key is an array, this parameter is ignored.
     * @return void
     */
    public function setData(string|array $key, mixed $value): void;

    /**
     * Render the view and return the content.
     *
     * @return string
     */
    public function render(): string;
}
