<?php

declare(strict_types=1);

namespace src\modules\api\service\crud;

use src\modules\api\exception\CategoryNotFoundHttpException;
use src\modules\api\models\BaseModel;
use src\modules\api\models\Category;
use yii\data\DataProviderInterface;
use yii\web\BadRequestHttpException;

/**
 * Class CrudCategoryService.
 */
class CrudCategoryService extends BaseCrudService implements CrudableInterface
{
    /**
     * {@inheritDoc}
     */
    public function findAllByParameters(array $params): DataProviderInterface
    {
        $filter = isset($params['filter']) ? $params['filter'] : [];
        $sort = isset($params['sort']) ? $params['sort'] : [];

        return $this->categoryRepository->findAllByParameters($filter, $sort);
    }

    /**
     * {@inheritDoc}
     */
    public function find(int $id): ?BaseModel
    {
        $model = $this->cacheCategoryService->get($id);
        if (!$model) {
            $model = $this->categoryRepository->findOneById($id);

            if (!$model instanceof BaseModel) {
                throw new CategoryNotFoundHttpException('Category not found');
            }
        }

        return $model;
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $params, string $scenario): Category
    {
        $category = $this->categoryRepository->createModel($params, $scenario);

        $this->categoryRepository->save($category);
        $this->cacheCategoryService->set($category, (int)$category->getId());

        return $category;
    }

    /**
     * {@inheritDoc}
     */
    public function update(int $id, array $params, string $scenario): Category
    {
        /** @var Category $category */
        $category = $this->categoryRepository->findOneById($id);

        if (!$category instanceof Category) {
            throw new CategoryNotFoundHttpException('Category not found');
        }

        $this->categoryRepository->update($category, $params, $scenario);
        $this->cacheCategoryService->set($category, (int)$category->getId());

        return $category;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($id, string $scenario): void
    {
        /** @var Category $category */
        $category = $this->categoryRepository->findOneById($id);

        if (!$category instanceof Category) {
            throw new CategoryNotFoundHttpException('Category not found');
        }

        if ($category->getArticles()) {
            throw new BadRequestHttpException('Category not empty');
        }

        $this->categoryRepository->update($category, ['status' => BaseModel::STATUS_DELETED], $scenario);

        $category->setStatus(BaseModel::STATUS_DELETED);
        $this->cacheCategoryService->set($category, (int)$category->getId());
    }
}
