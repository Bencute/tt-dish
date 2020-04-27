<?php

namespace dish\model\ar;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%ingredient}}".
 *
 * @property int $id
 * @property string $name
 * @property int $enable
 *
 * @property DishIngredient[] $dishIngredients
 * @property Dish[] $dishes
 */
class Ingredient extends ActiveRecord
{
    const ENABLE_TRUE = 1;
    const ENABLE_FALSE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ingredient}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['enable'], 'boolean'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
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
            'enable' => 'Enable',
        ];
    }

    /**
     * Gets query for [[DishIngredients]].
     *
     * @return ActiveQuery
     */
    public function getDishIngredients()
    {
        return $this->hasMany(DishIngredient::class, ['ingredient_id' => 'id']);
    }

    /**
     * Gets query for [[Dishes]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getDishes()
    {
        return $this->hasMany(Dish::class, ['id' => 'dish_id'])->viaTable('{{%dish_ingredient}}', ['ingredient_id' => 'id']);
    }
}
