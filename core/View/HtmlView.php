<?php
declare(strict_types=1);

namespace Core\View;

use Core\Http\Exceptions\ServerErrors\InternalErrorException;

final class HtmlView extends BaseView implements ViewInterface
{
    public string $template = '' {
        set {
            $this->template = $this->normalizePath($value);
        }
    }

    public ?string $layout = null {
        set {
            if (!$value) {
                $this->layout = '';
            }

            // Prevent double slashes in the layout path.
            $this->layout = $this->normalizePath('Layouts' . DS . $value);
        }
    }

    public function __construct(public readonly ViewRenderer $renderer)
    {
        parent::__construct();
    }

    /**
     * Normalize the path to ensure it does not contain double slashes.
     *
     * @param string $path
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

        if ($this->layout) {
            $content = $this->renderer->render($this->layout, array_merge($data, [
                'content' => $content,
            ]));
        }

        return $content;
    }
}
