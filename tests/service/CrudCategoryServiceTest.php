<?php

declare(strict_types=1);

namespace tests\service;

use src\modules\api\exception\CategoryNotFoundHttpException;
use src\modules\api\models\BaseModel;
use src\modules\api\models\Category;

/**
 * Class CrudCategoryServiceTest
 */
class CrudCategoryServiceTest extends BaseCrudServiceTest
{
    public function testFindCategoryFromCache(): void
    {
        $category = $this->createCategory();

        $this->cacheCategoryService
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($this->testCategory['id']))
            ->willReturn($category);

        $actualCategory = $this->crudCategoryService->find($this->testCategory['id']);

        $this->assertEquals($category, $actualCategory);
    }

    public function testFindCategoryFromDB(): void
    {
        $category = $this->createCategory();

        $this->cacheCategoryService
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($this->testCategory['id']))
            ->willReturn(null);

        $this->categoryRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->equalTo($this->testCategory['id']))
            ->willReturn($category);

        $actualCategory = $this->crudCategoryService->find($this->testCategory['id']);

        $this->assertEquals($category, $actualCategory);
    }

    public function testCreateCategory(): void
    {
        $category = $this->createCategory();

        $this->categoryRepository
            ->expects($this->once())
            ->method('createModel')
            ->with($this->equalTo($this->testCategory))
            ->willReturn($category);

        $this->categoryRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($category))
            ->willReturn(true);

        $this->cacheCategoryService
            ->expects($this->once())
            ->method('set')
            ->with($this->equalTo($category));

        $this->crudCategoryService->create($this->testCategory, Category::SCENARIO_CREATE);
    }

    public function testUpdateCategory(): void
    {
        $category = $this->createCategory();

        $this->categoryRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->equalTo($this->testCategory['id']))
            ->willReturn($category);

        $this->categoryRepository
            ->expects($this->once())
            ->method('update')
            ->with(
                $this->equalTo($category),
                $this->equalTo($this->testCategory),
                Category::SCENARIO_UPDATE
            );

        $this->cacheCategoryService
            ->expects($this->once())
            ->method('set')
            ->with($this->equalTo($category));

        $actualCategory = $this->crudCategoryService->update(
            (int)$this->testCategory['id'],
            $this->testCategory,
            Category::SCENARIO_UPDATE
        );
        $this->assertEquals($category, $actualCategory);
    }

    public function testUpdateCategoryNotFoundException(): void
    {
        $this->categoryRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->equalTo($this->testCategory['id']))
            ->willReturn(null);

        $this->expectException(CategoryNotFoundHttpException::class);
        $this->expectExceptionMessage('Category not found');

        $this->crudCategoryService->update((int)$this->testCategory['id'], $this->testCategory, Category::SCENARIO_UPDATE);
    }

     public function testDelete(): void
     {
         $category = $this->createCategory();

         $this->categoryRepository
             ->expects($this->once())
             ->method('findOneById')
             ->with($this->equalTo($this->testCategory['id']))
             ->willReturn($category);

         $this->categoryRepository
             ->expects($this->once())
             ->method('update')
             ->with(
                 $this->equalTo($category),
                 $this->equalTo(['status' => BaseModel::STATUS_DELETED]),
                 Category::SCENARIO_UPDATE
             );

         $this->cacheCategoryService
             ->expects($this->once())
             ->method('set')
             ->with($this->equalTo($category));

         $this->crudCategoryService->delete($this->testCategory['id'], Category::SCENARIO_UPDATE);
     }

     public function testDeleteCategoryNotFound(): void
     {
         $this->categoryRepository
             ->expects($this->once())
             ->method('findOneById')
             ->with($this->equalTo($this->testCategory['id']))
             ->willReturn(null);

         $this->expectException(CategoryNotFoundHttpException::class);
         $this->expectExceptionMessage('Category not found');

         $this->crudCategoryService->delete($this->testCategory['id'], Category::SCENARIO_UPDATE);
     }
}
