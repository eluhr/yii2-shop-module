<?php

namespace eluhr\shop\interfaces;

use eluhr\shop\models\Order;

interface PaymentProvider
{
    /**
     * @param string $currency
     * @return void
     */
    public function setCurrency(string $currency): void;

    /**
     * @param array $itemData
     * @return void
     */
    public function addItem(array $itemData): void;

    /**
     * @return bool
     */
    public function execute(): bool;

    /**
     * @param float $shippingCost
     * @return void
     */
    public function setShippingCost(float $shippingCost): void;

    /**
     * @return string
     */
    public function getSuccessUrl(): string;

    /**
     * @return string
     */
    public static function getType(): string;

    /**
     * @param \eluhr\shop\models\Order $order
     * @return Order|null
     */
    public function performCheckoutProcedure(Order $order): ?Order;

    /**
     * @return string
     */
    public function getPaymentDetails(): string;

}