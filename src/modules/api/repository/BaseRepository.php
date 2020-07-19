<?php

declare(strict_types=1);

namespace src\modules\api\repository;

use src\modules\api\models\BaseModel;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;

/**
 * Class BaseRepository.
 */
class BaseRepository extends ActiveQuery
{
    /**
     * @param array $filterParams
     * @param array $sortParams
     *
     * @return DataProviderInterface
     */
    public function findAllByParameters(array $filterParams = [], array $sortParams = []): DataProviderInterface
    {
        if (isset($filterParams['status'])) {
            $this->andFilterWhere([
                'status' => $filterParams['status']
            ]);
        } else {
            $this->andWhere(['not in', 'status', BaseModel::STATUS_DELETED]);
        }

        $this->orderBy([
            'created_at' => $sortParams['created_at'] ?? null,
            'updated_at' => $sortParams['updated_at'] ?? null,
        ]);

        return new ActiveDataProvider([
            'query' => $this
        ]);
    }

    /**
     * @param int $id
     *
     * @return BaseModel
     */
    public function findOneById(int $id): ?BaseModel
    {
        return $this->where(['id' => $id])->one();
    }

    /**
     * {@inheritDoc}
     */
    public function delete(int $id): bool
    {
        $article = $this->findOneById($id);

        return $article->delete();
    }


    /**
     * {@inheritDoc}
     */
    public function save(BaseModel $article): bool
    {
        return $article->save();
    }
}