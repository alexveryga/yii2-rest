<?php

declare(strict_types=1);

namespace src\modules\api\service\crud;

use src\modules\api\exception\ArticleNotFoundHttpException;
use src\modules\api\exception\CategoryNotFoundHttpException;
use src\modules\api\models\Article;
use src\modules\api\models\BaseModel;
use src\modules\api\models\Category;
use yii\data\DataProviderInterface;

/**
 * Class CrudArticleService.
 */
class CrudArticleService extends BaseCrudService implements CrudableInterface
{
    /**
     * {@inheritDoc}
     */
    public function findAllByParameters(array $params): DataProviderInterface
    {
        $filter = isset($params['filter']) ? $params['filter'] : [];
        $sort = isset($params['sort']) ? $params['sort'] : [];

        return $this->articleRepository->findAllByParameters($filter, $sort);
    }

    /**
     * {@inheritDoc}
     */
    public function find(int $id): ?BaseModel
    {
        $model = $this->cacheArticleService->get($id);
        if (!$model) {
            $model = $this->articleRepository->findOneById($id);

            if (!$model instanceof BaseModel) {
                throw new ArticleNotFoundHttpException('Article not found');
            }
        }

        return $model;
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $params, string $scenario): Article
    {
        if (empty($params['category_id'])) {
            throw new CategoryNotFoundHttpException('Category ID parameter not found');
        }

        $category = $this->categoryRepository->findOneById((int)$params['category_id']);
        if (!$category instanceof Category) {
            throw new CategoryNotFoundHttpException('Category not found');
        }

        $article = $this->articleRepository->createModel($params, $scenario);
        $article->setCategory($category);

        if ($this->articleRepository->save($article)) {
            $category->incrementArticle();
            $category->setScenario(Category::SCENARIO_UPDATE);
            $this->categoryRepository->save($category);

            $this->cacheArticleService->set($article, (int)$article->getId());

            return $article;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function update(int $id, array $params, string $scenario): Article
    {
        $article = $this->articleRepository->findOneById($id);
        if (!$article instanceof Article) {
            throw new ArticleNotFoundHttpException('Article not found');
        }

        $articleCategoryId = $article->getCategory()->getId();

        if (isset($params['category_id'])) {
            /** @var Category $category */
            $category = $this->categoryRepository->findOneById((int)$params['category_id']);

            if (!$category instanceof Category) {
                throw new CategoryNotFoundHttpException('Category not found');
            }

            /**
             * Article Category changed.
             */
            if ($articleCategoryId != (int)$params['category_id']) {

                /**
                 * Decrement old Category Article count.
                 */
                $category->setScenario(Category::SCENARIO_UPDATE);
                $category->decrementArticle();
                $this->categoryRepository->save($category);

                /**
                 * Increment new Category Article count.
                 */
                $newCategory = $this->categoryRepository->findOneById((int)$params['category_id']);
                $newCategory->setScenario(Category::SCENARIO_UPDATE);
                $newCategory->incrementArticle();

                $this->categoryRepository->save($newCategory);
            }

            $article->setCategory($category);
            $this->articleRepository->update($article, $params, $scenario);
            $this->cacheArticleService->set($article, (int)$article->getId());
        }

        return $article;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($id, string $scenario): void
    {
        /** @var Article $article */
        $article = $this->articleRepository->findOneById($id);

        if (!$article instanceof Article) {
            throw new ArticleNotFoundHttpException('Article not found');
        }

        if (!$article->getCategory() instanceof Category) {
            throw new CategoryNotFoundHttpException('Category not found');
        }

        /** @var Category $category */
        $category = $this->categoryRepository->findOneById(
            $article->getCategory()->getId()
        );

        // Update data.
        $this->articleRepository->update($article, ['status' => BaseModel::STATUS_DELETED], $scenario);

        // Cache.
        $article->setStatus(BaseModel::STATUS_DELETED);
        $this->cacheArticleService->set($article, (int)$article->getId());

        // Decrement counter.
        $category->setScenario(Category::SCENARIO_UPDATE);
        $category->decrementArticle();
        $this->categoryRepository->save($category);
    }
}
