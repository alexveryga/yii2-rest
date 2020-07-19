<?php

declare(strict_types=1);

namespace tests\service;

use src\modules\api\exception\ArticleNotFoundHttpException;
use src\modules\api\exception\CategoryNotFoundHttpException;
use src\modules\api\models\Article;
use src\modules\api\models\BaseModel;
use yii\data\DataProviderInterface;

/**
 * Class CrudArticleServiceTest
 */
class CrudArticleServiceTest extends BaseCrudServiceTest
{
    public function testFindArticleFromCache(): void
    {
        $article = $this->createArticle();

        $this->cacheArticleService
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($this->testArticle['id']))
            ->willReturn($article);

        $actualArticle = $this->crudArticleService->find($this->testArticle['id']);

        $this->assertEquals($article, $actualArticle);
    }

    public function testFindArticleFromDB(): void
    {
        $article = $this->createArticle();

        $this->cacheArticleService
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($this->testArticle['id']))
            ->willReturn(null);

        $this->articleRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->equalTo($this->testArticle['id']))
            ->willReturn($article);

        $actualArticle = $this->crudArticleService->find($this->testArticle['id']);

        $this->assertEquals($article, $actualArticle);
    }

    public function testCreateArticle(): void
    {
        $article = $this->createArticle();
        $category = $this->createCategory();

        $this->categoryRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->equalTo($this->testArticle['category_id']))
            ->willReturn($category);

        $this->articleRepository
            ->expects($this->once())
            ->method('createModel')
            ->with($this->equalTo($this->testArticle))
            ->willReturn($article);

        $article
            ->expects($this->once())
            ->method('setCategory')
            ->with($this->equalTo($category));

        $this->articleRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($article))
            ->willReturn(true);

        $category
            ->expects($this->once())
            ->method('incrementArticle');

        $this->categoryRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($category));

        $this->cacheArticleService
            ->expects($this->once())
            ->method('set')
            ->with($this->equalTo($article));

        $this->crudArticleService->create($this->testArticle, Article::SCENARIO_CREATE);
    }

    public function testCategoryIDNotFoundHttpException(): void
    {
        $testParams = [];

        $this->expectException(CategoryNotFoundHttpException::class);
        $this->expectExceptionMessage('Category ID parameter not found');

        $this->crudArticleService->create($testParams, Article::SCENARIO_CREATE);
    }

    public function testCategoryInstanceNotFoundHttpException(): void
    {
        $this->categoryRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->equalTo($this->testArticle['category_id']))
            ->willReturn(null);

        $this->expectException(CategoryNotFoundHttpException::class);
        $this->expectExceptionMessage('Category not found');

        $this->crudArticleService->create($this->testArticle, Article::SCENARIO_CREATE);
    }

    public function testUpdateArticle(): void
    {
        $article = $this->createArticle();
        $category = $this->createCategory();
        $newCategory = $this->createCategory();

        $this->articleRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->equalTo($this->testArticle['id']))
            ->willReturn($article);

        $this->categoryRepository
            ->expects($this->exactly(2))
            ->method('findOneById')
            ->with($this->equalTo($this->testArticle['category_id']))
            ->willReturn($category);

        $category
            ->expects($this->once())
            ->method('decrementArticle');

        $this->categoryRepository
            ->expects($this->exactly(2))
            ->method('save')
            ->with($this->equalTo($category));

        $this->categoryRepository
            ->expects($this->exactly(2))
            ->method('findOneById')
            ->with($this->equalTo($this->testArticle['category_id']))
            ->willReturn($newCategory);

        $this->categoryRepository
            ->expects($this->exactly(2))
            ->method('save')
            ->with($this->equalTo($newCategory));

        $this->articleRepository
            ->expects($this->once())
            ->method('update')
            ->with(
                $this->equalTo($article),
                $this->equalTo($this->testArticle),
                Article::SCENARIO_UPDATE
            );

        $this->cacheArticleService
            ->expects($this->once())
            ->method('set')
            ->with($this->equalTo($article));

        $actualArticle = $this->crudArticleService->update(
            (int)$this->testArticle['id'],
            $this->testArticle,
            Article::SCENARIO_UPDATE
        );
        $this->assertEquals($article, $actualArticle);
    }

    public function testUpdateArticleNotFoundException(): void
    {
        $this->articleRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->equalTo($this->testArticle['id']))
            ->willReturn(null);

        $this->expectException(ArticleNotFoundHttpException::class);
        $this->expectExceptionMessage('Article not found');

        $this->crudArticleService->update($this->testArticle['id'], $this->testArticle, Article::SCENARIO_UPDATE);
    }

    public function testCategoryNotFoundHttpException(): void
    {
        $article = $this->createArticle();

        $this->articleRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->equalTo($this->testArticle['id']))
            ->willReturn($article);

        $this->categoryRepository
            ->expects($this->exactly(1))
            ->method('findOneById')
            ->with($this->equalTo($this->testArticle['category_id']))
            ->willReturn(null);

        $this->expectException(CategoryNotFoundHttpException::class);
        $this->expectExceptionMessage('Category not found');

        $this->crudArticleService->update($this->testArticle['id'], $this->testArticle, Article::SCENARIO_UPDATE);
    }

    public function testDelete(): void
    {
        $article = $this->createArticle();
        $category = $this->createCategory();

        $this->articleRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->equalTo($this->testArticle['id']))
            ->willReturn($article);

        $this->articleRepository
            ->expects($this->once())
            ->method('update')
            ->with(
                $this->equalTo($article),
                $this->equalTo(['status' => BaseModel::STATUS_DELETED]),
                Article::SCENARIO_UPDATE
            );

        $article
            ->expects($this->exactly(2))
            ->method('getCategory')
            ->willReturn($category);

        $this->categoryRepository
            ->expects($this->exactly(1))
            ->method('findOneById')
            ->willReturn($category);

        $this->cacheArticleService
            ->expects($this->once())
            ->method('set')
            ->with($this->equalTo($article));

        $this->crudArticleService->delete($this->testArticle['id'], Article::SCENARIO_UPDATE);
    }

    public function testDeleteArticleNotFound(): void
    {
        $this->articleRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->equalTo($this->testArticle['id']))
            ->willReturn(null);

        $this->expectException(ArticleNotFoundHttpException::class);
        $this->expectExceptionMessage('Article not found');

        $this->crudArticleService->delete($this->testArticle['id'], Article::SCENARIO_UPDATE);
    }

    public function testFindAllArticlesWithParameters(): void
    {
        $parameters = [
            'sort' => [], 'filter' => [],
        ];

        $dataProvider = $this
            ->getMockBuilder(DataProviderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->articleRepository
            ->expects($this->once())
            ->method('findAllByParameters')
            ->with($this->equalTo($parameters['filter']), $this->equalTo($parameters['sort']))
            ->willReturn($dataProvider);

        $actualDataProvider = $this->crudArticleService->findAllByParameters($parameters);

        $this->assertEquals($dataProvider, $actualDataProvider);
    }
}
