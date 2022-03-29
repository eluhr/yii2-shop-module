<?php


namespace eluhr\shop\components;

use eluhr\shop\models\DiscountCode;
use eluhr\shop\models\ShoppingCartProduct;
use eluhr\shop\models\ShopSettings;
use eluhr\shop\models\Variant;
use Yii;
use yii\base\Component;
use yii\web\Session;

/**
 * @package eluhr\shop\components
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property \yii\web\Cookie $items
 * @property string $serialized
 * @property string $hash
 * @property ShoppingCartProduct[] $positions
 * @property int $count
 * @property bool $isEmpty
 * @property Session $_session
 */
class ShoppingCart extends Component
{
    public $cartId = __CLASS__ . '::v2';

    const EVENT_POSITION_PUT = 'putPosition';
    const EVENT_POSITION_UPDATE = 'updatePosition';
    const EVENT_BEFORE_POSITION_REMOVE = 'removePosition';
    const EVENT_CART_CHANGE = 'cartChange';
    const EVENT_COST_CALCULATION = 'costCalculation';

    protected $_session;

    /**
     * @var ShoppingCartProduct[]
     */
    protected $_positions = [];

    public function init()
    {
        $this->_session = Yii::$app->session;
        $this->loadFromSession();
    }

    public function loadFromSession()
    {
        if ($this->_session->get($this->cartId) !== null) {
            $this->setSerialized($this->_session->get($this->cartId));
        }
    }

    public function saveToSession()
    {
        $this->_session->set($this->cartId, $this->getSerialized());
    }

    /**
     * @param string $serialized
     */
    protected function setSerialized($serialized)
    {
        $this->_positions = unserialize($serialized, [
            'allowed_classes' => [
                ShoppingCartProduct::class
            ]
        ]);
    }

    /**
     * @return string
     */
    protected function getSerialized()
    {
        return serialize($this->_positions);
    }

    /**
     * @param ShoppingCartProduct $position
     * @param int $quantity
     */
    public function put($position, $quantity = 1)
    {
        $position->setQuantity($quantity);
        $this->_positions[$position->getPositionId()] = $position;
        $this->saveToSession();
    }

    /**
     * @param ShoppingCartProduct $position
     * @param int $quantity
     */
    public function updateQuantity($position, $quantity)
    {
        if ($quantity <= 0) {
            $this->remove($position);
            return;
        }
        $currentQuantity = $this->_positions[$position->getPositionId()]->getQuantity();
        $this->_positions[$position->getPositionId()]->setQuantity($currentQuantity + $quantity);

        $this->saveToSession();
    }

    /**
     * @param ShoppingCartProduct $position
     * @param int $quantity
     */
    public function setQuantity($position, $quantity)
    {
        if ($quantity <= 0) {
            if ($position->isDiscount) {
                DiscountCode::unuse();
            }
            $this->remove($position);
            return;
        }
        $this->_positions[$position->getPositionId()]->setQuantity($quantity);

        $this->saveToSession();
    }

    /**
     * Removes position from the cart
     *
     * @param ShoppingCartProduct $position
     */
    public function remove($position)
    {
        $this->removeById($position->getPositionId());
    }

    /**
     * Removes position from the cart by ID
     *
     * @param string $id
     */
    public function removeById($id)
    {
        unset($this->_positions[$id]);
        $this->saveToSession();
    }

    public function removeAll()
    {
        $this->_positions = [];
        $this->saveToSession();
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function hasPosition($id)
    {
        return isset($this->_positions[$id]);
    }

    /**
     * @param string $id
     *
     * @return ShoppingCartProduct|null
     */
    public function getPositionById($id)
    {
        if ($this->hasPosition($id)) {
            return $this->_positions[$id];
        }

        return null;
    }

    /**
     * @return ShoppingCartProduct[]
     */
    public function getPositions()
    {
        return $this->_positions;
    }

    /**
     * @param $positions
     */
    public function setPositions($positions)
    {
        $this->_positions = array_filter($positions, function ($position) {
            return $position->getQuantity() > 0;
        });

        $this->saveToSession();
    }

    /**
     * Returns true if cart is empty
     *
     * @return bool
     */
    public function getIsEmpty()
    {
        return count($this->_positions) === 0;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        $count = 0;
        foreach ($this->_positions as $position) {
            if ($position->isDiscount === false) {
                $count += $position->getQuantity();
            }
        }
        return $count;
    }

    /**
     * Return full cart cost as a sum of the individual positions costs
     *
     * @return int
     */
    public function getCost()
    {
        $cost = 0;
        foreach ($this->_positions as $position) {
            $cost += $position->getCost();
        }
        return round($cost, 2);
    }

    /**
     * compare if two carts are the same or detect if cart is changed
     *
     * @return string
     */
    public function getHash()
    {
        $data = [];
        foreach ($this->_positions as $position) {
            $data[] = [$position->getPositionId(), $position->getQuantity(), $position->price];
        }
        return md5(serialize($data));
    }

    public function applyDiscount(DiscountCode $discountCode)
    {
        if ($discountCode->isExpired() === false) {
            $position = new ShoppingCartProduct([
                'itemId' => $discountCode->code,
                'isDiscount' => true,
                'quantity' => 1
            ]);
            if ($this->hasPosition($position->positionId) === false && $discountCode->use()) {
                $this->put($position, 1);
                return true;
            }
        }
        return false;
    }

    private function items()
    {
        $items = [];
        foreach ($this->_positions as $position) {
            $item = $position->item();
            if ($item) {
                if ($position->isDiscount === false) {
                    $name = $item->product->title . ' - ' . $item->title;
                    $sku = $item->sku;
                } else {
                    $name = $item->label;
                    $sku = $item->code;
                }
                $items[] = [
                    'name' => $name,
                    'quantity' => $position->getQuantity(),
                    'sku' => $sku,
                    'price' => $item->getActualPrice(),
                    'isDiscount' => $position->isDiscount,
                    'discountValue' => $item instanceof DiscountCode ? $item->value : 0,
                    'discountType' => $item instanceof DiscountCode ? $item->type : null
                ];
            }
        }
        return $items;
    }

    public function total()
    {
        $total = 0;
        foreach ($this->items() as $item) {
            if ($item['isDiscount'] === false) {
                $total += $item['price'] * $item['quantity'];
            }
        }
        $discount = [
            DiscountCode::TYPE_PERCENT => 0,
            DiscountCode::TYPE_AMOUNT => 0
        ];

        foreach ($this->items() as $item) {
            if ($item['isDiscount'] === true) {
                    $discount[$item['discountType']] += $item['discountValue'];
            }
        }

        if ($discount[DiscountCode::TYPE_PERCENT] > 0) {
            $total *= 1 - ($discount[DiscountCode::TYPE_PERCENT] / 100);
        }
        if ($discount[DiscountCode::TYPE_AMOUNT] > 0) {
            $total -= $discount[DiscountCode::TYPE_AMOUNT];
        }

        $total += $this->shippingCost();

        return $total;
    }

    public function totalOfProducts()
    {
        $total = 0;
        foreach ($this->_positions as $position) {
            if ($position->isDiscount === false) {
                $total += $position->item()->price * $position->getQuantity();
            }
        }
        $total += $this->shippingCost();
        return $total;
    }

    public function shippingCost()
    {
        $shipping = 0;
        foreach ($this->_positions as $position) {
            if ($position->isDiscount === false) {
                /** @var Variant $item */
                $item = $position->item();
                $price = $item->product->shipping_price;
                if ((int)$item->product->staggering_shipping_cost !== 1 && $price > $shipping) {
                    $shipping = (float)$price;
                }
            }
        }
        foreach ($this->_positions as $position) {
            if ($position->isDiscount === false) {
                /** @var Variant $item */
                $item = $position->item();
                $price = $item->product->shipping_price;
                if ((int)$item->product->staggering_shipping_cost === 1) {
                    $shipping += (float)$price * $position->getQuantity();
                }
            }
        }
        return $shipping;
    }

    public function getDiscountCode()
    {
        foreach ($this->_positions as $position) {
            if ($position->isDiscount) {
                return $position->item();
            }
        }
        return null;
    }

    /**
     * @param string $orderId
     * @return \eluhr\shop\components\Payment|false
     * @throws \yii\web\HttpException
     */
    public function checkout($orderId, string $type)
    {
        /** @var \eluhr\shop\components\Payment $payment */
        $payment = \Yii::$app->payment;
        $payment->setProvider($type);
        foreach ($this->items() as $item) {
            $payment->addItem($item);
        }
        $payment->setShippingCost($this->shippingCost());
        $payment->setOrderId($orderId);
        if ($payment->execute()) {
            return $payment;
        }
        return false;
    }

    public function hasReachedMinValue()
    {
        return $this->total() >= ShopSettings::shopGeneralMinShoppingCartValue();
    }

    public function deliveryTimeText()
    {
        $highestMin = 0;
        $highestMax = 0;

        foreach ($this->_positions as $position) {
            $item = $position->item();
            if ($item instanceof Variant) {
                if (!empty($item->min_days_shipping_duration) && $item->min_days_shipping_duration > $highestMin) {
                    $highestMin = $item->min_days_shipping_duration;
                }
                if (!empty($item->max_days_shipping_duration) && $item->max_days_shipping_duration > $highestMax) {
                    $highestMax = $item->max_days_shipping_duration;
                }
            }
        }

        $min = null;
        $max = null;

        if ($highestMin > 0) {
            $min = $highestMin;
        }
        if ($highestMax > 0) {
            $max = $highestMax;
        }

        if (!empty($min) && !empty($max)) {
            return \Yii::t('shop', 'About {min}-{max} working days.', [
                'min' => $min,
                'max' => $max
            ]);
        }
        if (!empty($min)) {
            return \Yii::t('shop', 'About {min} working days.', [
                'min' => $min
            ]);
        }

        return \Yii::t('shop', 'About {min}-{max} working days.', [
            'min' => ShopSettings::shopProductMinDaysShippingDuration(),
            'max' => ShopSettings::shopProductMaxDaysShippingDuration()
        ]);
    }
}
