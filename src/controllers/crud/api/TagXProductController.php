<?php

namespace eluhr\shop\controllers\crud\api;

/**
 * This is the class for REST controller "TagXProductController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class TagXProductController extends \yii\rest\ActiveController
{
    public $modelClass = 'eluhr\shop\models\TagXProduct';
}
