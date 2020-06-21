<?php

namespace eluhr\shop\controllers\crud\api;

/**
* This is the class for REST controller "SettingController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SettingController extends \yii\rest\ActiveController
{
    public $modelClass = 'eluhr\shop\models\Setting';
}
