<?php

use src\modules\api\models\Article;
use src\modules\api\models\Category;
use src\modules\api\repository\ArticleRepository;
use src\modules\api\repository\CategoryRepository;
use src\modules\api\service\cache\CacheArticleService;
use src\modules\api\service\cache\CacheCategoryService;
use src\modules\api\service\crud\CrudArticleService;
use src\modules\api\service\crud\CrudCategoryService;
use yii\web\Request;
use yii\web\Response;

Yii::$container->setSingleton(Request::class);
Yii::$container->setSingleton(Response::class);

Yii::$container->set(ArticleRepository::class, ArticleRepository::class, [
    Article::class,
]);

Yii::$container->set(CategoryRepository::class, CategoryRepository::class, [
    Category::class,
]);

Yii::$container->set(CacheArticleService::class, CacheArticleService::class);
Yii::$container->set(CacheCategoryService::class, CacheCategoryService::class);

Yii::$container->set(
    CrudArticleService::class, CrudArticleService::class,
    [
        Yii::$container->get(ArticleRepository::class),
        Yii::$container->get(CategoryRepository::class),
        Yii::$container->get(CacheArticleService::class),
        Yii::$container->get(CacheCategoryService::class),
    ]
);

Yii::$container->set(
    CrudCategoryService::class, CrudCategoryService::class,
    [
        Yii::$container->get(ArticleRepository::class),
        Yii::$container->get(CategoryRepository::class),
        Yii::$container->get(CacheArticleService::class),
        Yii::$container->get(CacheCategoryService::class),
    ]
);
