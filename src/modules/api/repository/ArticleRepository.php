<?php

declare(strict_types=1);

namespace src\modules\api\repository;

use src\modules\api\models\Article;
use src\modules\api\models\BaseModel;

/**
 * Class ArticleRepository.
 */
class ArticleRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createModel(array $params, string $scenario): Article
    {
        /**
         * @var Article $article
         */
        $article = new $this->modelClass;
        $article->setScenario($scenario);
        $article->load($params, '');

        $article
            ->setCreatedAt()
            ->setUpdatedAt()
            ->setStatus();

        return $article;
    }

    /**
     * {@inheritDoc}
     */
    public function update(BaseModel $article, array $params, string $scenario): void
    {
        /** @var Article $article */
        $article->setScenario($scenario);
        $article->setAttributes($params);
        $article->setUpdatedAt();

        $article->save(false);
    }
}
