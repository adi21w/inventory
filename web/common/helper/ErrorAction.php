<?php

namespace common\helper;

use Yii;
use yii\web\ErrorAction as BaseErrorAction;

class ErrorAction extends BaseErrorAction
{
    public function run()
    {
        Yii::$app->controller->layout = 'error'; // GANTI layout DI SINI

        return parent::run();
    }
}
