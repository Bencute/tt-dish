<?php

namespace dish\model\ar;

use Exception;
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
     *
     */
    const NAME_FORMFIELD_INGREDIENTS = 'ingredients';

    /**
     * Если null то при сохранении не изменять связи ingredients
     * Если массив объектов Ingredient то сохранить только те что есть в массиве
     * Если пустой массив то буду удалены все связи(т.е. ничего не оставлять)
     * @var Ingredient[]|null
     */
    private $saveIngredients = null;

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

    /**
     * @param array $data
     * @param null $formName
     * @return bool
     * @throws InvalidConfigException
     */
    public function load($data, $formName = null)
    {
        if (isset($data[$formName ?? $this->formName()][self::NAME_FORMFIELD_INGREDIENTS])) {
            $ingredientsId = $data[$formName ?? $this->formName()][self::NAME_FORMFIELD_INGREDIENTS] ?? [];
            $this->saveIngredients = Ingredient::findAll($ingredientsId);
        }

        return parent::load($data, $formName);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = $this->getDb()->beginTransaction();
        try {
            if (parent::save($runValidation, $attributeNames)){
                if (is_array($this->saveIngredients)){
                    $deleteIngredients = array_udiff(
                        $this->ingredients,
                        $this->saveIngredients,
                        function($model, $save) {
                            return $model->id - $save->id;
                        }
                    );

                    $newIngredients = array_udiff(
                        $this->saveIngredients,
                        $this->ingredients,
                        function($save, $model) {
                            return $save->id - $model->id;
                        }
                    );

                    foreach ($deleteIngredients as $deleteIngredient) {
                        $this->unlink('ingredients', $deleteIngredient, true);
                    }

                    foreach ($newIngredients as $newIngredient) {
                        $this->link('ingredients', $newIngredient);
                    }
                }

                $transaction->commit();
                return true;
            }
        }
        catch (Exception $e) {
            $transaction->rollback();
            if (YII_DEBUG){
                throw $e;
            }
        }

        return false;
    }

    /**
     * @return Ingredient[]|null
     */
    public function getSaveIngredients()
    {
        return $this->saveIngredients;
    }
}
