<?php

namespace eluhr\shop\components\providers;


use eluhr\shop\models\Order;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 */
class DirectDebitPayment extends BasePaymentProvider
{
    public function execute(): bool
    {
        return true;
    }

    public function getApprovalLink(): string
    {
        return \Yii::$app->payment->getSuccessUrl();
    }

    static function getType(): string
    {
        return Order::TYPE_PREPAYMENT;
    }

    public function performCheckoutProcedure(Order $order): ?Order
    {
        $order->status = Order::STATUS_RECEIVED;
        return $order;
    }
}
