<?php

namespace dish\controllers\frontend;

use yii\web\Controller;

/**
 * Default controller for the `dish` module
 */
class DefaultController extends Controller
{

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
        return $this->render('index');
    }
}
