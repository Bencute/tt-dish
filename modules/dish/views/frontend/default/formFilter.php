<?php

use yii\widgets\ActiveForm;
use dish\model\ar\Ingredient;
use dish\model\forms\FormFilterByIngredients;

/* @var $ingredients Ingredient[] */
/* @var $model FormFilterByIngredients */

?>

<?php $form = ActiveForm::begin(); ?>
    <?=$form->errorSummary($model, ['header' => ''])?>
    <?php foreach ($ingredients as $ingredient) { ?>
        <label class="btn btn-primary">
            <input type="checkbox"
                   name="<?=$model->formName() . '[ingredients][]'?>"
                   autocomplete="off"
                   value="<?=$ingredient->id?>"
                   <?=(array_search($ingredient->id, $model->ingredients) !== false) ? 'CHECKED' : ''?>
            > <?=$ingredient->name?>
        </label>
    <?php } ?>

    <input class="btn btn-success" type="submit" name="<?=$model->formName() . '[submit]'?>" value="filter">

<?php ActiveForm::end(); ?>
