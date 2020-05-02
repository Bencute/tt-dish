<?php

namespace dish\controllers\frontend;

use dish\DishModule;
use dish\model\forms\FormFilterByIngredients;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `dish` module
 */
class DefaultController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->setViewPath('@dish/views/frontend/default');
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $ingredients = DishModule::getIngredients();

        $model = new FormFilterByIngredients();

        $dataProvider = null;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            var_dump($model);
            // TODO filter logic
            $dataProvider = DishModule::getDishDataProviderByIngredients($model->ingredients);
        }

        return $this->render('index', [
            'ingredients' => $ingredients,
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
}
