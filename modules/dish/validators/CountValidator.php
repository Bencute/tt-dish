<?php

namespace dish\validators;

use dish\DishModule;
use Yii;
use yii\validators\Validator;

/**
 * Валидатор проверяет количество элементов в массиве
 *
 * Class CountValidator
 * @package dish\validators
 */
class CountValidator extends Validator
{
    /**
     * Максимальное количество элементов
     *
     * @var integer|null
     */
    public $max;

    /**
     * @var string
     */
    public $maxMessage;

    /**
     * Минимальное количество элементов
     *
     * @var integer|null
     */
    public $min;

    /**
     * @var string
     */
    public $minMessage;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if ($this->maxMessage === null)
            $this->maxMessage = DishModule::t('dish', 'Максимальное количество {count}');

        if ($this->minMessage === null)
            $this->minMessage = DishModule::t('dish', 'Минимальное количество {count}');
    }

    /**
     *  {@inheritdoc}
     */
    public function validateAttribute($model, $attribute)
    {
        if (is_array($model->$attribute)) {
            $count = count($model->$attribute);

            if ($this->max !== null && $count > $this->max) {
                $this->addError($model, $attribute, $this->maxMessage, ['count' => $this->max]);
            }

            if ($this->min !== null && $count < $this->min) {
                $this->addError($model, $attribute, $this->minMessage, ['count' => $this->min]);
            }
        }
    }
}