<?php

namespace eluhr\shop\models;

use Yii;
use \eluhr\shop\models\base\OrderItem as BaseOrderItem;

/**
 * This is the model class for table "sp_order_item".
 */
class OrderItem extends BaseOrderItem
{
    public function checkout($isDiscount)
    {
        if ($isDiscount) {
            return true;
        }
        if ($this->save()) {
            $variant = Variant::findOne($this->variant_id);
            if ($variant) {
                if (!$variant->product->is_inventory_independent) {
                    $variant->stock -= $this->quantity;
                    return $variant->save();
                }
                return true;
            }
        }
        Yii::error($this->getErrors());
        return false;
    }
}
