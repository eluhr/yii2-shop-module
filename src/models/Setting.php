<?php

namespace eluhr\shop\models;

use Yii;
use \eluhr\shop\models\base\Setting as BaseSetting;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sp_settings".
 */
class Setting extends BaseSetting
{
    public function beforeValidate()
    {
        if ($this->key === ShopSettings::SHOP_CHECKOUT_PAYMENT_PROVIDERS && is_array($this->value)) {
            $this->value = implode(',', $this->value);
        }

        return parent::beforeValidate();
    }
}
