<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use dish\model\ar\Dish;
use dish\model\ar\Ingredient;
use dish\model\forms\FormFilterByIngredients;

/* @var $ingredients Ingredient[] */
/* @var $model FormFilterByIngredients */
/* @var $dataProvider ActiveDataProvider|null */
/* @var $dishes Dish[] */

?>
<div class="dish-default-index">
    <h1>Dishes</h1>

    <?= $this->render('formFilter', [
            'ingredients' => $ingredients,
            'model' => $model,
        ]
    )?>

    <?php if (!is_null($dataProvider)) {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'name',
                [
                    'class' => 'yii\grid\DataColumn',
                    'label' => 'Ингредиенты',
                    'value' => function ($data) {
                        return implode(
                            ', ',
                            array_map(
                                function($item){
                                    return $item->name;
                                },
                                $data->ingredients
                            )
                        );
                    },
                ],
            ],
        ]);
    } ?>
</div>
