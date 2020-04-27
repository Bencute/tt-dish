<?php

use yii\db\Migration;

/**
 * Class m200427_155554_init
 */
class m200427_155554_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создание таблицы ингредиентов
        $this->createTable('{{%ingredient}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique(),
            'enable' => $this->boolean()->notNull()->defaultValue(true),
        ]);

        $this->createIndex(
            'idx-ingredient-id',
            '{{%ingredient}}',
            'id'
        );

        // Создание таблицы блюд
        $this->createTable('{{%dish}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
        ]);

        $this->createIndex(
            'idx-dish-id',
            '{{%dish}}',
            'id'
        );

        // Создание промежуточной таблицы
        $this->createTable('{{%dish_ingredient}}', [
            'dish_id' => $this->integer(),
            'ingredient_id' => $this->integer(),
            'PRIMARY KEY(dish_id, ingredient_id)',
        ]);

        $this->createIndex(
            'idx-dish_ingredient-dish_id',
            '{{%dish_ingredient}}',
            'dish_id'
        );

        $this->createIndex(
            'idx-dish_ingredient-ingredient_id',
            '{{%dish_ingredient}}',
            'ingredient_id'
        );

        $this->addForeignKey(
            'fk-dish_ingredient-dish_id',
            '{{%dish_ingredient}}',
            'dish_id',
            '{{%dish}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-dish_ingredient-ingredient_id',
            '{{%dish_ingredient}}',
            'ingredient_id',
            '{{%ingredient}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-dish_ingredient-ingredient_id',
            '{{%dish_ingredient}}'
        );

        $this->dropForeignKey(
            'fk-dish_ingredient-dish_id',
            '{{%dish_ingredient}}'
        );

        $this->dropIndex(
            'idx-dish_ingredient-ingredient_id',
            '{{%dish_ingredient}}'
        );

        $this->dropIndex(
            'idx-dish_ingredient-dish_id',
            '{{%dish_ingredient}}'
        );

        $this->dropIndex(
            'idx-dish-id',
            '{{%dish}}'
        );

        $this->dropIndex(
            'idx-ingredient-id',
            '{{%ingredient}}'
        );

        $this->dropTable('{{%dish}}');
        $this->dropTable('{{%ingredient}}');
    }
}
