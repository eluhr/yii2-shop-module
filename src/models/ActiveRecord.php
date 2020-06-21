<?php


namespace eluhr\shop\models;

use bedezign\yii2\audit\AuditTrailBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord as BaseActiveRecord;
use yii\db\Expression;

class ActiveRecord extends BaseActiveRecord
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'value' => new Expression('NOW()')
        ];
        $behaviors['audit'] = [
            'class' => AuditTrailBehavior::class
        ];
        return $behaviors;
    }
}
