<?php

namespace dish;

use dish\model\ar\Dish;
use Yii;
use dish\model\ar\Ingredient;
use yii\data\ActiveDataProvider;

/**
 * Dish module definition class
 */
class DishModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'dish\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/dish/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'basePath'       => '@dish/messages',
            'fileMap'        => [
                'modules/dish/main' => 'main.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/dish/' . $category, $message, $params, $language);
    }

    /**
     * @return array|Ingredient[]
     */
    public static function getIngredients(){
        return Ingredient::find()->all();
    }

    public static function getDishDataProviderByIngredients($ingredients = [])
    {
        $query = Dish::find($ingredients);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $dataProvider;
    }
}
