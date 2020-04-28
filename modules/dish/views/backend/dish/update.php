<?php

use yii\helpers\Html;
use dish\model\ar\Ingredient;

/* @var $this yii\web\View */
/* @var $model dish\model\ar\Dish */
/* @var $ingredients Ingredient[] */
/* @var $saveIngredients Ingredient[]|null */

$this->title = 'Update Dish: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Dishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dish-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'ingredients' => $ingredients,
        'saveIngredients' => $saveIngredients,
    ]) ?>

</div>
