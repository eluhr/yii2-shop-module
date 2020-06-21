<?php

namespace eluhr\shop\controllers\crud\api;

/**
 * This is the class for REST controller "OrderController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class OrderController extends \yii\rest\ActiveController
{
    public $modelClass = 'eluhr\shop\models\Order';
}
