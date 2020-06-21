<?php

namespace eluhr\shop\controllers\crud\api;

/**
 * This is the class for REST controller "DiscountCodeController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class DiscountCodeController extends \yii\rest\ActiveController
{
    public $modelClass = 'eluhr\shop\models\DiscountCode';
}
