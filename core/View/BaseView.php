<?php
declare(strict_types=1);

namespace Core\View;

use ArrayObject;

abstract readonly class BaseView implements ViewInterface
{
    protected ArrayObject $templateData;

    public function __construct()
    {
        $this->templateData = new ArrayObject();
    }

    /**
     * @inheritDoc
     */
    public function getTemplateData(): array
    {
        return $this->templateData->getArrayCopy();
    }

    /**
     * @inheritDoc
     */
    public function setTemplateData(string $key, mixed $value): void
    {
        $this->templateData->offsetSet($key, $value);
    }
}
