<?php

namespace eluhr\shop\components\traits;

use Yii;

trait FiltersTrait
{
    public $filterIds;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['crud'][] = 'filterIds';
        return $scenarios;
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['filterIds'] = Yii::t('shop', 'Tags');
        return $attributeLabels;
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['filterIds', 'safe'];
        return $rules;
    }
}
