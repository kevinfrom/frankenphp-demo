<?php

declare(strict_types=1);

namespace Core\View;

use ArrayObject;

abstract class BaseView implements ViewInterface
{
    protected ArrayObject $templateData;

    public function __construct()
    {
        $this->templateData = new ArrayObject();
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->templateData->getArrayCopy();
    }

    /**
     * @inheritDoc
     */
    public function setData(string|array $key, mixed $value = null): void
    {
        if (is_string($key)) {
            $this->templateData->offsetSet($key, $value);

            return;
        }

        $this->templateData->exchangeArray(array_merge($this->getData(), $key));
    }
}
