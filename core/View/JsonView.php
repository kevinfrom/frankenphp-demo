<?php
declare(strict_types=1);

namespace Core\View;

final readonly class JsonView extends BaseView implements ViewInterface
{
    /**
     * @inheritDoc
     */
    public function getContentType(): string
    {
        return 'application/json';
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return json_encode($this->getTemplateData());
    }
}
