<?php

use src\modules\api\Api;
use yii\web\JsonParser;
use yii\web\JsonResponseFormatter;
use yii\web\Response;
use yii\caching\MemCache;

return [
    'id' => 'rest-api',
    'basePath' => dirname(__DIR__),
    'homeUrl' => '/',
    'components' => [
        'urlManager' => require(__DIR__ . '/urlManager.php'),
        'request' => [
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => JsonParser::class,
            ]
        ],
        'response' => [
            'formatters' => [
                Response::FORMAT_JSON => [
                    'class' => JsonResponseFormatter::class,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'cache' => [
            'class' => MemCache::class,
            'useMemcached' => true,
            'servers' => [
                [
                    'host' => 'api_memcached_1',
                    'port' => 11211,
                    'weight' => 100,
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php')
    ],
    'modules' => [
        'api' => [
            'class' => Api::class,
        ],
    ],
];
