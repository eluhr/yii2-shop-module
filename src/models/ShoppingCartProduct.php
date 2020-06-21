<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\models;

use yii\base\BaseObject;

/**
 * @package project\modules\frontend\models
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property int $quantity
 * @property int $positionId
 * @property int $cost
 * @property int $price
 * @property bool $isDiscount
 */
class ShoppingCartProduct extends BaseObject
{
    public $itemId;
    public $isDiscount = false;
    public $price;
    public $_quantity;

    protected static $_model;

    /**
     * @return string
     */
    public function getPositionId()
    {
        return md5(json_encode(['itemId' => $this->itemId]));
    }

    /**
     * @return integer
     */
    public function getCost()
    {
        return $this->getPrice() * $this->quantity;
    }

    /**
     * @return integer
     */
    public function getPrice()
    {
        return $this->item()->price;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->_quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return (int)$this->_quantity;
    }

    /**
     * @return null|Variant|DiscountCode
     */
    public function item()
    {
        if (!isset(static::$_model[$this->itemId])) {
            if ($this->isDiscount) {
                $model = DiscountCode::find()->andWhere(['code' => $this->itemId])->active()->one();
            } else {
                $model = Variant::find()->andWhere(['id' => $this->itemId])->active()->one();
            }

            static::$_model[$this->itemId] = $model;
        }
        return static::$_model[$this->itemId];
    }
}
