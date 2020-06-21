<?php

namespace eluhr\shop\controllers\crud\api;

/**
 * This is the class for REST controller "VariantController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class VariantController extends \yii\rest\ActiveController
{
    public $modelClass = 'eluhr\shop\models\Variant';
}
