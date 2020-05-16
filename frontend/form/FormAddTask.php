<?php


namespace frontend\form;


use Sys;
use system\general\LoadAttributesTrait;
use system\web\Form;

/**
 * Class FormAddTask
 * @package frontend\form
 */
class FormAddTask extends Form
{
    use LoadAttributesTrait;

    public ?string $name = '';
    public ?string $email = '';
    public ?string $content = '';

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            ['email', 'vRequire'],
            ['email', 'vEmail'],
            ['email', 'vLength', 'params' => ['max' => 64]],

            ['name', 'vRequire'],
            ['name', 'vLength', 'params' => ['min' => 3, 'max' => 255]],

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
            'name',
            'email',
            'content',
        ];
    }
}