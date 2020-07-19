<?php

use yii\web\UrlManager;

return [
    'class' => UrlManager::class,
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        // Article
        'PUT,PATCH api/article/<id:\\d+>' => 'api/article/update',
        'DELETE api/article/<id:\\d+>'    => 'api/article/delete',
        'GET,HEAD api/article/<id:\\d+>'  => 'api/article/view',
        'POST api/article'                => 'api/article/create',
        'GET,HEAD api/article'            => 'api/article/index',

        // Category
        'PUT,PATCH api/category/<id:\\d+>' => 'api/category/update',
        'DELETE api/category/<id:\\d+>'    => 'api/category/delete',
        'GET,HEAD api/category/<id:\\d+>'  => 'api/category/view',
        'POST api/category'                => 'api/category/create',
        'GET,HEAD api/category'            => 'api/category/index',
    ]
];
