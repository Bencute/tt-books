<?php


namespace frontend\form;


use system\general\LoadAttributesTrait;
use system\web\Form;

/**
 * Class FormLogin
 * @package frontend\form
 */
class FormLogin extends Form
{
    use LoadAttributesTrait;

    public ?string $email = '';
    public ?string $name = '';
    public ?string $password = null;
    public bool $remember = false;

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
//            ['email', 'vRequire'],
//            ['email', 'vEmail'],
            ['name', 'vRequire'],
            ['password', 'vRequire'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return [
            'email',
            'name',
            'password',
            'remember',
        ];
    }
}