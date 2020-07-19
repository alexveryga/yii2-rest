<?php

declare(strict_types=1);

namespace src\modules\api\models;

use yii\db\ActiveQuery;

/**
 * Class Article
 *
 * @property integer $id
 * @property string  $title
 * @property string  $description
 * @property string  $text
 * @property string  $created_at
 * @property string  $updated_at
 * @property integer $category_id
 * @property string  $status
 */
class Article extends BaseModel
{
    /**
     * @return array|string[]
     */
    public function extraFields(): array
    {
        return [
            'category',
        ];
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'article';
    }

    /**
     * @return array|string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'category_id',
            'author',
            'title',
            'description',
            'text',
            'created_at',
            'updated_at',
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
            [['category_id'], 'integer'],
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
            self::SCENARIO_CREATE =>  ['category_id', 'author', 'title', 'description', 'text'],
            self::SCENARIO_UPDATE =>  ['category_id', 'title', 'description', 'text', 'status'],
        ];
    }

    public function scenarios()
    {
        return $this->getCustomScenarios();
    }

    /**
     * @return ActiveQuery
     */
    public function getCategoryRelation(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->getCategoryRelation()->one();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $dateFormat
     *
     * @return $this
     */
    public function setUpdatedAt(string $dateFormat = self::SQL_DATETIME_FORMAT): Article
    {
        $this->updated_at = date($dateFormat);

        return $this;
    }

    /**
     * @return $this
     */
    public function setCreatedAt(): Article
    {
        $this->created_at = date(self::SQL_DATETIME_FORMAT);

        return $this;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status = BaseModel::STATUS_NEW): Article
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param Category $category
     *
     * @return $this
     */
    public function setCategory(Category $category): Article
    {
        $this->category_id = $category->getId();

        return $this;
    }
}
