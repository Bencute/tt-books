<?php


namespace frontend\application;


use frontend\services\ServiceProcessIsbn;

/**
 * {@inheritdoc}
 * Class Web Application for books test task
 *
 * @package frontend\application
 */
class ApplicationBook extends Application
{
    /**
     * {@inheritdoc}
     */
    public string $defaultController = 'book';
    /**
     * @var ServiceProcessIsbn
     */

    private ServiceProcessIsbn $serviceProcessIsbn;

    /**
     * @return ServiceProcessIsbn
     */
    public function getServiceProcessIsbn(): ServiceProcessIsbn
    {
        return $this->serviceProcessIsbn ?? ($this->serviceProcessIsbn = new ServiceProcessIsbn());
    }
}