<?php

namespace eluhr\shop\components\interfaces;

/**
 * --- PROPERTIES ---
 *
 */
interface PaymentInterface
{
    /**
     * @param array $itemData
     * @return void
     */
    public function addItem(array $itemData);

    /**
     * @return bool|mixed
     */
    public function execute();
}
