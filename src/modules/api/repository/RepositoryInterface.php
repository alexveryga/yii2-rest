<?php

declare(strict_types=1);

namespace src\modules\api\repository;

use src\modules\api\models\BaseModel;
use yii\data\DataProviderInterface;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface
{
    /**
     * Return model by given id.
     *
     * @param int $id
     *
     * @return BaseModel
     */
    public function findOneById(int $id): ?BaseModel;

    /**
     * Return list of models by request parameters.
     *
     * @param array $filterParams
     * @param array $sortParams
     *
     * @return DataProviderInterface
     */
    public function findAllByParameters(array $filterParams = [], array $sortParams = []): DataProviderInterface;

    /**
     * @param array  $params
     * @param string $scenario
     *
     * @return BaseModel
     */
    public function createModel(array $params, string $scenario): BaseModel;

    /**
     * @param BaseModel $article
     *
     * @return bool
     */
    public function save(BaseModel $article): bool;

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param BaseModel $article
     * @param array     $params
     * @param string    $scenario
     */
    public function update(BaseModel $article, array $params, string $scenario): void;
}
