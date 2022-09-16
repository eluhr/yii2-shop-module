<?php

namespace eluhr\shop\interfaces;

use eluhr\shop\models\Order;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 */
interface ExternalPaymentProvider extends PaymentProvider
{
    /**
     * @return string
     */
    public function getApprovalLink(): string;

    /**
     * @param string $approvalLink
     * @return void
     */
    public function setApprovalLink(string $approvalLink): void;

    /**
     * @return array
     */
    public static function identifiableGetParams(): array;

    /**
     * @param array $condition
     * @return Order|null
     */
    public function findOrder(array $condition = []): ?Order;
}
