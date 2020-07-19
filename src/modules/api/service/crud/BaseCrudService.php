<?php

declare(strict_types=1);

namespace src\modules\api\service\crud;

use src\modules\api\repository\ArticleRepository;
use src\modules\api\repository\CategoryRepository;
use src\modules\api\service\cache\CacheArticleService;
use src\modules\api\service\cache\CacheCategoryService;

/**
 * Class BaseCrudService
 */
class BaseCrudService
{
    /**
     * @var ArticleRepository
     */
    protected ArticleRepository $articleRepository;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var CacheArticleService
     */
    protected CacheArticleService $cacheArticleService;

    /**
     * @var CacheCategoryService
     */
    protected CacheCategoryService $cacheCategoryService;

    /**
     * CrudArticleService constructor
     *
     * @param ArticleRepository    $articleRepository
     * @param CategoryRepository   $categoryRepository
     * @param CacheArticleService  $cacheArticleService
     * @param CacheCategoryService $cacheCategoryService
     */
    public function __construct(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        CacheArticleService $cacheArticleService,
        CacheCategoryService $cacheCategoryService
    ) {
        $this->articleRepository    = $articleRepository;
        $this->categoryRepository   = $categoryRepository;
        $this->cacheArticleService  = $cacheArticleService;
        $this->cacheCategoryService = $cacheCategoryService;
    }
}
