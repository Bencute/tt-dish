<?php

use yii\helpers\Html;
use dish\model\ar\Ingredient;

/* @var $this yii\web\View */
/* @var $model dish\model\ar\Dish */
/* @var $ingredients Ingredient[] */
/* @var $saveIngredients Ingredient[]|null */

$this->title = 'Create Dish';
$this->params['breadcrumbs'][] = ['label' => 'Dishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dish-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'ingredients' => $ingredients,
        'saveIngredients' => $saveIngredients,
    ]) ?>

</div>
