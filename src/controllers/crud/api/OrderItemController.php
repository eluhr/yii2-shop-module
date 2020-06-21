<?php

namespace eluhr\shop\controllers\crud\api;

/**
 * This is the class for REST controller "OrderItemController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class OrderItemController extends \yii\rest\ActiveController
{
    public $modelClass = 'eluhr\shop\models\OrderItem';
}
