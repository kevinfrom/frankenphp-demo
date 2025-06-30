<?php

declare(strict_types=1);

namespace Core\View;

use Core\Http\Exceptions\ServerErrors\InternalErrorException;

final class HtmlView extends BaseView implements ViewInterface
{
    protected string $template = '';

    protected ?string $layout = null;

    public function __construct(public readonly ViewRenderer $renderer)
    {
        parent::__construct();
    }

    /**
     * Get template path relative to the Views directory.
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * Set teh template path relative to the Views directory.
     *
     * @param string $template
     *
     * @return void
     */
    public function setTemplate(string $template): void
    {
        // Prevent double slashes in the template path.
        $this->template = $this->normalizePath($template);
    }

    /**
     * Get layout path.
     *
     * @return string|null
     */
    public function getLayout(): ?string
    {
        return $this->layout;
    }

    /**
     * Set the layout path.
     *
     * @param null|string $layout
     *
     * @return void
     */
    public function setLayout(?string $layout): void
    {
        if ($layout) {
            // Prevent double slashes in the layout path.
            $layout = $this->normalizePath('Layouts' . DS . $layout);
        }

        $this->layout = $layout;
    }

    /**
     * Normalize the path to ensure it does not contain double slashes.
     *
     * @param string $path
     *
     * @return string
     */
    protected function normalizePath(string $path): string
    {
        return preg_replace('/[\/\\\]{2,}/', '', $path);
    }

    /**
     * @inheritDoc
     */
    public function getContentType(): string
    {
        return 'text/html';
    }

    /**
     * @inheritDoc
     * @throws InternalErrorException
     */
    public function render(): string
    {
        $data = $this->getData();

        $content = $this->renderer->render($this->template, $data);

        if ($this->getLayout()) {
            $content = $this->renderer->render($this->getLayout(), array_merge($data, [
                'content' => $content,
            ]));
        }

        return $content;
    }
}
