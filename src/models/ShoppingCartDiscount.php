<?php


namespace eluhr\shop\models;

use PayPal\Api\Payment;
use Ramsey\Uuid\Uuid;
use \Yii;
use yii\base\Model;

/**
 * @property null $discountCodeId
 */
class ShoppingCartDiscount extends Model
{
    public $discount_code;
    private $_isActive = false;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            'discount_code',
            'checkIfValid'
        ];
        return $rules;
    }

    public function checkIfValid()
    {
        $model = DiscountCode::find()->andWhere(['code' =>$this->discount_code])->active()->one();
        if ($model === null) {
            $this->addError('discount_code', Yii::t('shop', 'Discount Code code is expired or does not exist'));
        }
    }

    public function check()
    {
        $discountCode = Yii::$app->shoppingCart->getDiscountCode();
        if ($discountCode) {
            $this->discount_code = $discountCode->code;
            $this->_isActive = true;
        } else {
            $this->_isActive = false;
        }
    }

    public function isActive()
    {
        return $this->_isActive;
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['discount_code'] = Yii::t('shop', 'Discount Code');
        return $attributeLabels;
    }

    public function apply()
    {
        $discountCode = DiscountCode::find()->andWhere(['code' => $this->discount_code])->active()->one();
        if ($discountCode) {
            return Yii::$app->shoppingCart->applyDiscount($discountCode);
        }
        return false;
    }
}
