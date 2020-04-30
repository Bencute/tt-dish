<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dish\model\ar\Dish;
use dish\model\ar\Ingredient;

/* @var $this View */
/* @var $model Dish */
/* @var $form ActiveForm */
/* @var $ingredients Ingredient[] */
/* @var $saveIngredients Ingredient[]|null */


$inputIngredientField = str_replace(
    "\n",
    "",
    $this->render('_formIngredient', [
            'model' => $model,
            'ingredients' => $ingredients,
        ]
    )
);

$this->registerJs(<<< EOT_JS_CODE
class ListIngredient {
    inputField = '';
    listBlock = '';
    count = 0;
    maxCount = 5;

    constructor(inputField, listBlock) {
        this.inputField = inputField;
        this.listBlock = listBlock;
        this.count = this.listBlock.children.length;
        
        this.listBlock.querySelectorAll('.js-item-ingredient').forEach((element) => {
            this.initDeleteLink(element);
        }, this);
    }
    
    add() {
        if (this.listBlock.children.length < this.maxCount) {
            this.listBlock.insertAdjacentHTML('beforeend', this.inputField);
            let listIngredients = this.listBlock.querySelectorAll('.js-item-ingredient');
            let lastIngredient = listIngredients[listIngredients.length - 1];
            this.initDeleteLink(lastIngredient);
        }
    }
    
    remove(item) {
        item.remove();
    }
    
    initDeleteLink(itemIngredient) {
        let delLink = document.createElement('a');
        delLink.href = '';
        delLink.innerHTML = 'Delete';
        var refObj = this;
        delLink.addEventListener('click', function (e) {
            refObj.remove(itemIngredient);
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
        });
        
        itemIngredient.append(delLink);
    }
}

(function () {
    var listIngredient = new ListIngredient('{$inputIngredientField}', document.querySelector('.js-ingredients'));
    
    var linkAddIngredient = document.querySelector('.js-add-ingredient');
    linkAddIngredient.listIngredient = listIngredient;
    linkAddIngredient.addEventListener('click', function (e) {
        this.listIngredient.add();
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
    });
})();
EOT_JS_CODE
);
?>

<div class="dish-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="js-ingredients">
        <?=Html::error($model, 'saveIngredients')?>

        <?php if (is_array($saveIngredients)) { ?>
            <?php foreach ($saveIngredients as $saveIngredient) { ?>
                <?= $this->render('_formIngredient', [
                    'model' => $model,
                    'ingredients' => $ingredients,
                    'selectIngredient' => $saveIngredient,
                ]
                )?>
            <?php } ?>
        <?php } ?>
    </div>

    <div class="form-group">
        <a href class="js-add-ingredient">Add ingredient</a>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
