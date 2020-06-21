<?php

namespace eluhr\shop\controllers\crud\api;

/**
 * This is the class for REST controller "ProductController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class ProductController extends \yii\rest\ActiveController
{
    public $modelClass = 'eluhr\shop\models\Product';
}
