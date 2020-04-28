<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use dish\model\ar\Dish;
use dish\model\ar\Ingredient;

/* @var $this View */
/* @var $model Dish */
/* @var $ingredients Ingredient[] */
/* @var $selectIngredient Ingredient| not isset */

?>
<div class="form-group form-inline js-item-ingredient">
    <?=Html::dropDownList(
        $model->formName() . '[' . Dish::NAME_FORMFIELD_INGREDIENTS . '][]',
        isset($selectIngredient) ? $selectIngredient->id : null,
        ArrayHelper::map($ingredients, 'id', 'name'),
        [
            'prompt'=>'Select ingredient',
            'class' => 'form-control'
        ]
    )?>
</div>
