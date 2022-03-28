<?php

namespace eluhr\shop\components\providers;

use eluhr\shop\interfaces\PaymentProvider;
use eluhr\shop\models\Order;
use yii\base\BaseObject;
use yii\helpers\Url;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 */
abstract class BasePaymentProvider extends BaseObject implements PaymentProvider
{
    protected $_items = [];
    protected $_shippingCost;
    protected $_currency;
    protected $_successUrl;

    /**
     * @param string $currency
     * @return void
     */
    public function setCurrency(string $currency): void
    {
        $this->_currency = $currency;
    }

    /**
     * @param float $shippingCost
     * @return void
     */
    public function setShippingCost(float $shippingCost): void
    {
        $this->_shippingCost = $shippingCost;
    }

    /**
     * @param array $itemData
     * @return void
     */
    public function addItem(array $itemData): void
    {
        $this->_items[] = $itemData;
    }

    /**
     * @return float
     */
    public function getTotalPrice()
    {
        $itemsSum = 0;
        foreach ($this->_items as $item) {
            $itemsSum += $item['price'] ?? 0;
        }
        return $itemsSum + $this->_shippingCost;
    }

    /**
     * @return string
     */
    public function getSuccessUrl(): string
    {
        return Url::to(['/shop/shopping-cart/success', 'orderId' => \Yii::$app->payment->getOrderId(),
            'type' => static::getType()], true);
    }

    public function getPaymentDetails(): string
    {
        return '';
    }
}
