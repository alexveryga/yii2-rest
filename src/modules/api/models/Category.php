<?php

declare(strict_types=1);

namespace src\modules\api\models;

use yii\db\ActiveQuery;

/**
 * Class Category
 *
 * @property integer $id
 * @property string  $title
 * @property string  $description
 * @property string  $text
 * @property string  $created_at
 * @property string  $updated_at
 * @property integer $articles_counter
 * @property string  $status
 */
class Category extends BaseModel
{
    public static function tableName(): string
    {
        return 'category';
    }

    public function attributes(): array
    {
        return [
            'id',
            'title',
            'description',
            'text',
            'created_at',
            'updated_at',
            'articles_counter',
            'status',
        ];
    }

    /**
     * @return array|array[]
     */
    public function rules()
    {
        $allScenarios = $this->getCustomScenarios();

        // Published not required
        $allScenarios[self::SCENARIO_CREATE] = \array_diff($allScenarios[self::SCENARIO_CREATE], ['published']);

        return [
            [$allScenarios[self::SCENARIO_CREATE], 'required', 'on' => self::SCENARIO_CREATE],
            [$allScenarios[self::SCENARIO_UPDATE], 'required', 'on' => self::SCENARIO_UPDATE],
            [['title', 'description'], 'string', 'max' => 100],
            [['created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['status'], 'safe']
        ];
    }

    /**
     * Set validation Scenarios.
     *
     * @return \string[][]
     */
    public function getCustomScenarios()
    {
        return [
            self::SCENARIO_CREATE =>  ['title', 'description', 'text'],
            self::SCENARIO_UPDATE =>  ['title', 'description', 'text', 'status'],
        ];
    }

    public function scenarios()
    {
        return $this->getCustomScenarios();
    }

    public function getArticlesRelation(): ActiveQuery
    {
        return $this->hasMany(Article::class, ['category_id' => 'id']);
    }

    /**
     * @return Article[]
     */
    public function getArticles(): array
    {
        return $this->getArticlesRelation()->all();
    }

    /**
     * @param string $dateFormat
     *
     * @return $this
     */
    public function setUpdatedAt(string $dateFormat = self::SQL_DATETIME_FORMAT): Category
    {
        $this->updated_at = date($dateFormat);

        return $this;
    }

    /**
     * @return $this
     */
    public function setCreatedAt(): Category
    {
        $this->created_at = date(self::SQL_DATETIME_FORMAT);

        return $this;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status = BaseModel::STATUS_NEW): Category
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return $this
     */
    public function incrementArticle(): Category
    {
        $this->articles_counter++;

        return $this;
    }

    /**
     * @return $this
     */
    public function decrementArticle(): Category
    {
        $this->articles_counter--;

        return $this;
    }
}
