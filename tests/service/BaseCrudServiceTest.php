<?php

namespace tests\service;

use Codeception\PHPUnit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use src\modules\api\models\Article;
use src\modules\api\models\Category;
use src\modules\api\repository\ArticleRepository;
use src\modules\api\repository\CategoryRepository;
use src\modules\api\service\cache\CacheArticleService;
use src\modules\api\service\cache\CacheCategoryService;
use src\modules\api\service\crud\CrudableInterface;
use src\modules\api\service\crud\CrudArticleService;
use src\modules\api\service\crud\CrudCategoryService;

/**
 * Class BaseCrudServiceTest.
 */
abstract class BaseCrudServiceTest extends TestCase
{
    /**
     * @var array
     */
    protected $testArticle = [
        'id'          => 1,
        'category_id' => 1,
        'author'      => 'author',
        'title'       => 'title',
        'description' => 'description',
        'text'        => 'text'
    ];

    /**
     * @var array
     */
    protected $testCategory = [
        'id'          => 1,
        'title'       => 'title',
        'description' => 'description',
        'text'        => 'text'
    ];

    /**
     * @var MockObject|ArticleRepository
     */
    protected ArticleRepository $articleRepository;

    /**
     * @var MockObject|CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var MockObject|CrudableInterface
     */
    protected CrudableInterface $crudArticleService;

    /**
     * @var MockObject|CrudableInterface
     */
    protected CrudableInterface $crudCategoryService;

    /**
     * @var MockObject|CacheArticleService
     */
    protected CacheArticleService $cacheArticleService;

    /**
     * @var MockObject|CacheCategoryService
     */
    protected CacheCategoryService $cacheCategoryService;

    /**
     * @return Article|MockObject
     */
    protected function createArticle(): Article
    {
        return $this
            ->getMockBuilder(Article::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return Category|MockObject
     */
    protected function createCategory(): Category
    {
        return $this
            ->getMockBuilder(Category::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function setUp(): void
    {
        $this->articleRepository = $this
            ->getMockBuilder(ArticleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->categoryRepository = $this
            ->getMockBuilder(CategoryRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cacheArticleService = $this
            ->getMockBuilder(CacheArticleService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cacheCategoryService = $this
            ->getMockBuilder(CacheCategoryService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->crudArticleService = new CrudArticleService(
            $this->articleRepository,
            $this->categoryRepository,
            $this->cacheArticleService,
            $this->cacheCategoryService
        );

        $this->crudCategoryService = new CrudCategoryService(
            $this->articleRepository,
            $this->categoryRepository,
            $this->cacheArticleService,
            $this->cacheCategoryService
        );
    }
}