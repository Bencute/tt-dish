<?php

namespace dish\model\ar;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%dish_ingredient}}".
 *
 * @property int $dish_id
 * @property int $ingredient_id
 *
 * @property Dish $dish
 * @property Ingredient $ingredient
 */
class DishIngredient extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dish_ingredient}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dish_id', 'ingredient_id'], 'required'],
            [['dish_id', 'ingredient_id'], 'integer'],
            [['dish_id', 'ingredient_id'], 'unique', 'targetAttribute' => ['dish_id', 'ingredient_id']],
            [['dish_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dish::class, 'targetAttribute' => ['dish_id' => 'id']],
            [['ingredient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ingredient::class, 'targetAttribute' => ['ingredient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dish_id' => 'Dish ID',
            'ingredient_id' => 'Ingredient ID',
        ];
    }

    /**
     * Gets query for [[Dish]].
     *
     * @return ActiveQuery
     */
    public function getDish()
    {
        return $this->hasOne(Dish::class, ['id' => 'dish_id']);
    }

    /**
     * Gets query for [[Ingredient]].
     *
     * @return ActiveQuery
     */
    public function getIngredient()
    {
        return $this->hasOne(Ingredient::class, ['id' => 'ingredient_id']);
    }
}
