<?php

use yii\db\Migration;

/**
 * Class m200719_045239_init
 */
class m200719_045239_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'category',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(255)->defaultValue(''),
                'description' => $this->text(),
                'text' => $this->text(),
                'status' => $this->string(50)->defaultValue('new'),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'articles_counter' => $this->integer(),
            ]
        );

        $this->createTable(
            'article',
            [
                'id' => $this->primaryKey(),
                'category_id' => $this->integer()->notNull(),
                'author' => $this->string(255)->defaultValue(''),
                'title' => $this->string(255)->defaultValue(''),
                'description' => $this->text(),
                'status' => $this->string(50)->defaultValue('new'),
                'text' => $this->text(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ]
        );

        $this->createIndex('idx_category-created_at', 'category', 'created_at');
        $this->createIndex('idx_category-updated_at', 'category', 'updated_at');

        $this->addForeignKey('fk-article-category', 'article',  'category_id', 'category', 'id', 'CASCADE');
        $this->createIndex('idx_article_created_at', 'article', 'created_at');
        $this->createIndex('idx_article_updated_at', 'article', 'updated_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');
        $this->dropTable('article');
    }
}
