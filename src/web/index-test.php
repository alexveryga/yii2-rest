<?php

error_reporting(E_ALL);

// TEST ENV
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', dirname(dirname(__DIR__)));

// Bootstraping tests environment
require(__DIR__ . '/../../tests/codeception/api/_bootstrap.php');

$config = require(__DIR__ . '/../../tests/codeception/config/functional.php');

(new yii\web\Application($config))->run();
