<?php

declare(strict_types=1);

namespace src\modules\api\repository;

use src\modules\api\models\BaseModel;
use src\modules\api\models\Category;

/**
 * Class CategoryRepository.
 */
class CategoryRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createModel(array $params, string $scenario): Category
    {
        /** @var Category $category */
        $category = new $this->modelClass;
        $category->setScenario($scenario);
        $category->load($params, '');
        $category
            ->setCreatedAt()
            ->setUpdatedAt()
            ->setStatus();

        return $category;
    }

    /**
     * {@inheritDoc}
     */
    public function update(BaseModel $category, array $params, string $scenario): void
    {
        /** @var Category $category */
        $category->setScenario($scenario);
        $category->setAttributes($params);
        $category->setUpdatedAt();

        $category->save(false);
    }
}
