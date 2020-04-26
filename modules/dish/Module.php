<?php

namespace modules\dish;

/**
 * dish module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'dish\controllers';

    public function init()
    {
        parent::init();

        $this->setAliases([
            '@dish' => __DIR__
        ]);
    }
}
