<?php

use yii\data\ActiveDataProvider;
use dish\model\ar\Ingredient;
use dish\model\forms\FormFilterByIngredients;

/* @var $ingredients Ingredient[] */
/* @var $model FormFilterByIngredients */
/* @var $dataProvider ActiveDataProvider */

?>
<div class="dish-default-index">
    <h1>Dishes</h1>

    <?= $this->render('formFilter', [
            'ingredients' => $ingredients,
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]
    )?>
</div>
