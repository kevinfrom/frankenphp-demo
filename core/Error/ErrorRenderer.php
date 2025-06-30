<?php

declare(strict_types=1);

namespace Core\Error;

use Core\Container\ContainerException;
use Core\Container\NotFoundException;
use Core\Http\Exceptions\HttpException;
use Core\Http\Exceptions\ServerErrors\InternalErrorException;
use Core\Http\Response\ServerResponseInterface;
use Core\View\HtmlView;
use Core\View\ViewRenderer;
use Throwable;

use function Core\config;
use function Core\container;
use function Core\response;

final readonly class ErrorRenderer implements ErrorRendererInterface
{
    /**
     * The template rendered for 5xx errors.
     *
     * @var string
     */
    public string $template500;

    /**
     * The template rendered for 4xx errors.
     *
     * @var string
     */
    public string $template400;

    /**
     * The template rendered for development errors.
     *
     * @var string
     */
    public string $templateDevError;

    public function __construct()
    {
        $this->template400      = config()->get('Error.templates.400') ?: '';
        $this->template500      = config()->get('Error.templates.500') ?: '';
        $this->templateDevError = config()->get('Error.templates.dev') ?: '';
    }

    /**
     * Load template for the given error code.
     *
     * @param int $code
     *
     * @return string
     * @throws InternalErrorException
     */
    protected function loadTemplate(int $code): string
    {
        $template = config()->get("Error.templates.$code");

        if (!$template) {
            throw new InternalErrorException("Template for error code $code not configured.");
        }

        return $template;
    }

    /**
     * @inheritDoc
     * @throws NotFoundException
     * @throws ContainerException
     */
    public function renderException(Throwable|HttpException $exception): ServerResponseInterface
    {
        if (!$exception instanceof HttpException) {
            $exception = new InternalErrorException();
        }

        $template = $this->template500;
        if ($exception->getCode() < 500) {
            $template = $this->template400;
        }

        $view = new HtmlView(container()->get(ViewRenderer::class));
        $view->setTemplate($template);
        $view->setLayout('error');

        if (config()->get('debug') && $exception->getCode() !== 404) {
            $view->setTemplate($this->templateDevError);
            $view->setLayout(null);
        }

        $view->setData('exception', $exception);

        return response($view, $exception->getCode());
    }
}
