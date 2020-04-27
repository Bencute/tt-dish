<?php

namespace dish\model\ar;

/**
 * This is the ActiveQuery class for [[Ingredient]].
 *
 * @see Ingredient
 */
class IngredientQuery extends \yii\db\ActiveQuery
{
    /**
     * @param int $status Ingredient::ENABLE_TRUE || Ingredient::ENABLE_FALSE
     * @return IngredientQuery
     */
    public function enabled($status = Ingredient::ENABLE_TRUE)
    {
        return $this->andWhere('[[enable]]=' . $status);
    }

    /**
     * {@inheritdoc}
     * @return Ingredient[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Ingredient|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
