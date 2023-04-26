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

    /**
     * overwrite \yii\db\BaseActiveRecord::instantiate() to be able to overload model classes returned by ActiveQuery
     *
     * The default implementation return new static() models, but Yii::createObject()
     * must be used to be able to overload model classes via DI
     *
     * @param array $row
     *
     * @return ActiveRecord|object
     * @throws \yii\base\InvalidConfigException
     */
    public static function instantiate($row)
    {
        return \Yii::createObject(static::class);
    }
}
