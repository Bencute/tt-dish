<?php

namespace dish\model\ar;

/**
 * This is the ActiveQuery class for [[Dish]].
 *
 * @see Dish
 */
class DishQuery extends \yii\db\ActiveQuery
{

    /**
     * {@inheritdoc}
     * @return Dish[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Dish|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
