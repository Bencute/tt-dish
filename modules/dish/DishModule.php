<?php

namespace dish;

use Exception;
use Yii;
use dish\model\ar\Ingredient;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use Yii\db\ActiveQuery;
use yii\db\conditions\AndCondition;
use yii\db\Expression;
use dish\model\ar\Dish;
use dish\model\ar\DishIngredient;

/**
 * Dish module definition class
 */
class DishModule extends Module
{
    /**
     * Минимальное количество ингредиентов для вывода блюд
     */
    const MIN_COUNT_INGREDIENTS = 2;

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
     * @return Ingredient[]
     */
    public static function getIngredients(){
        return Ingredient::find()->all();
    }

    /**
     * @param $ingredients integer[]
     * @return ActiveQuery
     */
    public static function getDishActiveQueryByIngredients($ingredients = []) {
        if (empty($ingredients))
            throw new Exception('Parameter $ingredients cannot be empty');

        $minCountIngredients = self::getMinCountIngredientsForShowDishes();

        // Подзапрос количества совпавших выбранных ингредиентов
        // SELECT COUNT(*) FROM dish_ingredient as di where di.`ingredient_id` IN (1,4,6,3) AND di.dish_id = d.id
        $queryDishCountIngredients = DishIngredient::find()
            ->select(new Expression('COUNT(*)'))
            ->from(['di' => DishIngredient::tableName()])
            ->andWhere(['IN', 'di.ingredient_id', $ingredients])
            ->andWhere(['di.dish_id' => new Expression('d.id')]);


        // Подзапрос отключенных ингредиентов
        // SELECT * FROM ingredient as i WHERE i.enable = 0 AND i.id IN (
        //     (SELECT ingredient_id FROM dish_ingredient as di where di.dish_id = d.id)
        // )
        $queryDisabledIngredients = Ingredient::find()
            ->from(['i' => Ingredient::tableName()])
            ->andWhere(['i.enable' => Ingredient::ENABLE_FALSE])
            ->andWhere([
                'IN',
                'i.id',
                DishIngredient::find()
                    ->select(['ingredient_id'])
                    ->from(['di' => DishIngredient::tableName()])
                    ->andWhere(['di.dish_id' => new Expression('d.id')])
            ]);

        // Условие существования в блюде всех выбранных ингредиентов
        // EXISTS (SELECT * FROM dish_ingredient as di where di.`ingredient_id` = 1 AND di.dish_id = d.id) AND
        // EXISTS (SELECT * FROM dish_ingredient as di where di.`ingredient_id` = 4 AND di.dish_id = d.id) AND
        // EXISTS (SELECT * FROM dish_ingredient as di where di.`ingredient_id` = 6 AND di.dish_id = d.id) AND
        // EXISTS (SELECT * FROM dish_ingredient as di where di.`ingredient_id` = 3 AND di.dish_id = d.id) AND
        $whereExistsIngredients = [];
        foreach ($ingredients as $ingredient) {
            $whereExistsIngredients[] = [
                'exists',
                DishIngredient::find()
                    ->from(['di' => DishIngredient::tableName()])
                    ->andWhere(['di.ingredient_id' => $ingredient])
                    ->andWhere(['di.dish_id' => new Expression('d.id')])
            ];
        }
        $whereExistsIngredients = new AndCondition($whereExistsIngredients);

        // Подзапрос ингредиентов которые есть в блюде но не выбраны
        // SELECT * FROM dish_ingredient as di where di.`ingredient_id` NOT IN (1,4,6,3) AND di.dish_id = d.id) AND
        //     (SELECT COUNT(*) FROM dish_ingredient as di where di.`ingredient_id` IN (1,4,6,3) AND di.dish_id = d.id) > 1
        $queryDishIngredientNotInListIngredients = DishIngredient::find()
            ->from(['di' => DishIngredient::tableName()])
            ->andWhere(['NOT IN', 'di.ingredient_id', $ingredients])
            ->andWhere(['di.dish_id' => new Expression('d.id')])
            ->andWhere(['<=', $minCountIngredients, $queryDishCountIngredients]);

        // Подзапрос блюд в которых есть только выбранные ингредиенты
        // SELECT * FROM `dish` as d WHERE
        //     EXISTS (SELECT * FROM dish_ingredient as di where di.`ingredient_id` = 1 AND di.dish_id = d.id) AND
        //     EXISTS (SELECT * FROM dish_ingredient as di where di.`ingredient_id` = 4 AND di.dish_id = d.id) AND
        //     EXISTS (SELECT * FROM dish_ingredient as di where di.`ingredient_id` = 6 AND di.dish_id = d.id) AND
        //     EXISTS (SELECT * FROM dish_ingredient as di where di.`ingredient_id` = 3 AND di.dish_id = d.id) AND
        //     NOT EXISTS (
        //         SELECT * FROM dish_ingredient as di where di.`ingredient_id` NOT IN (1,4,6,3) AND di.dish_id = d.id) AND
        //             (SELECT COUNT(*) FROM dish_ingredient as di where di.`ingredient_id` IN (1,4,6,3) AND di.dish_id = d.id) > 1
        $queryDishWithFullIngredients = Dish::find()
            ->from(['d' => Dish::tableName()])
            ->andWhere($whereExistsIngredients)
            ->andWhere(['NOT EXISTS', $queryDishIngredientNotInListIngredients]);


        $selectSubQuery = ([
            "d.id"
        ]);
        $from = (['d' => Dish::tableName()]);

        // $subqueryFullIngredients условие подзапрос id блюд в которых есть только выбранные ингредиенты
        $subqueryFullIngredients = Dish::find();
        $subqueryFullIngredients->addSelect($selectSubQuery);
        $subqueryFullIngredients->from($from);
        $subqueryFullIngredients->andWhere(['NOT EXISTS', $queryDisabledIngredients]);
        $subqueryFullIngredients->andWhere(['NOT EXISTS', $queryDishIngredientNotInListIngredients]);
        $subqueryFullIngredients->andWhere($whereExistsIngredients);
        $subqueryFullIngredients->andWhere(['EXISTS', $queryDishWithFullIngredients]);

        // $subqueryNotFullIngredients условие подзапрос id блюд в которых есть НЕ только выбранные ингредиенты но и другие
        $subqueryNotFullIngredients = Dish::find();
        $subqueryNotFullIngredients->addSelect($selectSubQuery);
        $subqueryNotFullIngredients->from($from);
        $subqueryNotFullIngredients->andWhere(['NOT EXISTS', $queryDisabledIngredients]);
        $subqueryNotFullIngredients->andWhere(['<=', $minCountIngredients, $queryDishCountIngredients]);
        $subqueryNotFullIngredients->andWhere(['NOT EXISTS', $queryDishWithFullIngredients]);

        $subqueryFullIngredients->union($subqueryNotFullIngredients);

        // Основной запрос для выборки моделей
        $queryDish = Dish::find();
        $queryDish->addSelect([
            "*",
            'ci' => $queryDishCountIngredients
        ]);
        $queryDish->from($from);
        $queryDish->andWhere([
            'IN',
            'd.id',
            $subqueryFullIngredients
        ]);

        $queryDish->orderBy(['ci' => SORT_DESC]);

        $queryDish->with(['ingredients']);

        return $queryDish;
    }

    /**
     * @param array $ingredients
     * @return ActiveDataProvider
     * @throws Exception
     */
    public static function getDishDataProviderByIngredients($ingredients = [])
    {
        $query = self::getDishActiveQueryByIngredients($ingredients);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => false,
        ]);
    }

    /**
     * @return int
     */
    private static function getMinCountIngredientsForShowDishes()
    {
        return self::MIN_COUNT_INGREDIENTS;
    }
}
