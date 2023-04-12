<?php

namespace eluhr\shop\controllers\crud\api;

/**
 * This is the class for REST controller "VatController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class VatController extends \yii\rest\ActiveController
{
    public $modelClass = 'eluhr\shop\models\Vat';
}
