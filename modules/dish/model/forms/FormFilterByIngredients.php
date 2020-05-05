<?php

namespace dish\model\forms;

use yii\base\Model;

class FormFilterByIngredients extends Model
{
    const NAME_FORMFIELD_INGREDIENTS = 'ingredients';

    const MAX_INGREDIENTS = 5;
    const MIN_INGREDIENTS = 2;

    /**
     * @var integer[]
     */
    public $ingredients = [];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['ingredients', '\dish\validators\CountValidator',
                'max' => self::MAX_INGREDIENTS,
                'min' => self::MIN_INGREDIENTS,
                'minMessage' => 'Выберите больше игредиентов',
                'maxMessage' => 'Максимум {count, plural, one{# ингредиент} few{# ингредиента} other{# ингредиентов}}',
            ],
            ['ingredients', 'exist',
                'allowArray' => true,
                'targetClass' => '\dish\model\ar\Ingredient',
                'targetAttribute' => 'id',
                'message' => 'Неизвестный ингредиент',
            ]
        ];
    }
}
