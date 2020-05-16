<?php


namespace frontend\form;


use system\general\LoadAttributesTrait;
use system\web\Form;

class FormEditTask extends Form
{
    use LoadAttributesTrait;

    public ?string $content = '';
    public bool $performed = false;

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            ['content', 'vRequire'],
            ['content', 'vLength', 'params' => ['max' => 1000]],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return [
            'content',
            'performed',
        ];
    }
}