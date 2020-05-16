<?php
defined('DEBUG_MODE') or define('DEBUG_MODE', true);

// require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../system/autoload.php';

$config = require __DIR__ . '/../config/main.php';

Sys::setBaseDir(dirname(__DIR__));

(new frontend\application\ApplicationBook($config))->init();