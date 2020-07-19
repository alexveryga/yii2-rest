<?php

declare(strict_types=1);

namespace src\modules\api\service\crud;

use src\modules\api\models\BaseModel;
use yii\data\DataProviderInterface;

/**
 * Interface CrudableInterface.
 */
interface CrudableInterface
{
    /**
     * @param int $id
     *
     * @return BaseModel
     */
    public function find(int $id): ?BaseModel;

    /**
     * Create instance.
     *
     * @param array  $params
     * @param string $scenario
     *
     * @return BaseModel
     */
    public function create(array $params, string $scenario): BaseModel;

    /**
     * Update instance.
     *
     * @param int    $id
     * @param array  $params
     * @param string $scenario
     *
     * @return BaseModel
     */
    public function update(int $id, array $params, string $scenario): BaseModel;

    /**
     * Delete instance.
     *
     * @param int    $id
     * @param string $scenario
     */
    public function delete(int $id, string $scenario): void;

    /**
     * Return list of instances by sorting and filer parameters.
     *
     * @param array $params
     *
     * @return DataProviderInterface
     */
    public function findAllByParameters(array $params): DataProviderInterface;
}
