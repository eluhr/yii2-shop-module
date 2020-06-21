<?php

namespace eluhr\shop\controllers\crud\api;

/**
 * This is the class for REST controller "TagController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class TagController extends \yii\rest\ActiveController
{
    public $modelClass = 'eluhr\shop\models\Tag';
}
