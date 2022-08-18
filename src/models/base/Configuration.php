<?php

namespace eluhr\shop\models\base;

use eluhr\shop\models\ActiveRecord;
use Ramsey\Uuid\Uuid;

/**
 * --- PROPERTIES ---
 *
 * @property string $id
 * @property string $variant_id
 * @property string $json
 *
 * @author Elias Luhr
 */
abstract class Configuration extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sp_configuration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            [
                'id',
                'variant_id',
                'json'
            ],
            'required'
        ];
        $rules[] = [
            'id',
            'unique'
        ];
        $rules[] = [
            'variant_id',
            'exist',
            'skipOnError' => true,
            'targetClass' => Variant::class,
            'targetAttribute' => ['variant_id' => 'id']
        ];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (empty($this->id)) {
            $this->id = Uuid::uuid4()->toString();
        }
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['id'] = \Yii::t('shop', 'ID');
        $attributeLabels['variant_id'] = \Yii::t('shop', 'Variant ID');
        $attributeLabels['json'] = \Yii::t('shop', 'JSON');
        return $attributeLabels;
    }
}
