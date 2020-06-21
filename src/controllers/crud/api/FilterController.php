<?php

namespace eluhr\shop\controllers\crud\api;

/**
 * This is the class for REST controller "FilterController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class FilterController extends \yii\rest\ActiveController
{
    public $modelClass = 'eluhr\shop\models\Filter';
}
