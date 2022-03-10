<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\models;

use eluhr\shop\components\ShoppingCart;
use eluhr\shop\components\validators\StockValidator;
use Yii;
use yii\base\Model;

/**
 * @package eluhr\shop\models
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property null|Product $product
 * @property string $positionId
 * @property Variant|null $item
 * @property string $id
 */
class ShoppingCartModify extends Model
{
    public $quantity;
    public $variantId;
    public $extraInfo;

    public const MAX_QUANTITY = 99;

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['required'] = [
            [
                'quantity',
                'variantId'
            ],
            'required'
        ];
        $rules['safe'] = [
            'extraInfo',
            'safe'
        ];
        $rules['product'] = [
            'variantId',
            'exist',
            'skipOnError' => true,
            'targetClass' => Variant::class,
            'targetAttribute' => ['variantId' => 'id']
        ];
        $rules['min-max'] = [
            'quantity',
            StockValidator::class
        ];
        return $rules;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['quantity'] = Yii::t('shop', 'Amount');
        $attributeLabels['discountId'] = Yii::t('shop', 'Discount');
        $attributeLabels['variantId'] = Yii::t('shop', 'Variante');
        $attributeLabels['extraInfo'] = Yii::t('shop', 'Zusätzliche Optionen');
        return $attributeLabels;
    }

    /**
     * @return array|false
     */
    public static function quantityList()
    {
        $quantities = range(1, self::MAX_QUANTITY);
        return array_combine($quantities, $quantities);
    }

    /**
     * @return null|Variant
     */
    public function getItem()
    {
        return Variant::find()->andWhere(['id' => $this->variantId])->active()->one();
    }

    /**
     * @return bool
     */
    public function updateCurrentShoppingCart()
    {
        if ($this->validate()) {
            /** @var ShoppingCart $shoppingCart */
            $shoppingCart = Yii::$app->shoppingCart;

            $position = new ShoppingCartProduct([
                'itemId' => $this->variantId,
                'price' => $this->item->getActualPrice(),
                'quantity' => $this->quantity,
                'extraInfo' => $this->extraInfo
            ]);

            if ($shoppingCart->hasPosition($position->positionId)) {
                $shoppingCart->updateQuantity($position, $this->quantity);
            } else {
                $shoppingCart->put($position, $this->quantity);
            }
            return true;
        }
        Yii::error($this->errors, __METHOD__);
        return false;
    }
}
