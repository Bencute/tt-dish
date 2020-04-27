<?php

namespace dish\model\ar;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%dish}}".
 *
 * @property int $id
 * @property string $name
 *
 * @property DishIngredient[] $dishIngredients
 * @property Ingredient[] $ingredients
 */
class Dish extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dish}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[DishIngredients]].
     *
     * @return ActiveQuery
     */
    public function getDishIngredients()
    {
        return $this->hasMany(DishIngredient::class, ['dish_id' => 'id']);
    }

    /**
     * Gets query for [[Ingredients]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getIngredients()
    {
        return $this->hasMany(Ingredient::class, ['id' => 'ingredient_id'])->viaTable('{{%dish_ingredient}}', ['dish_id' => 'id']);
    }
}
