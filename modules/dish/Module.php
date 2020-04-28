<?php

namespace dish;

use dish\model\ar\Ingredient;

/**
 * dish module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'dish\controllers';

    /**
     * @return array|Ingredient[]
     */
    public function getIngredients(){
        return Ingredient::find()->all();
    }
}
