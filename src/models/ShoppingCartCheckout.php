<?php


namespace eluhr\shop\models;

use eluhr\shop\components\validators\DependencyValidator;
use PayPal\Api\Payment;
use Yii;
use yii\base\Model;

/**
 * @property null $discountCodeId
 * @property Payment $_payment
 */
class ShoppingCartCheckout extends Model
{
    public $first_name;
    public $surname;
    public $email;
    public $street_name;
    public $house_number;
    public $postal;
    public $city;
    public $has_different_delivery_address;
    public $delivery_first_name;
    public $delivery_surname;
    public $delivery_street_name;
    public $delivery_house_number;
    public $delivery_postal;
    public $delivery_city;
    public $discount_code;
    public $type;
    public $date_of_birth;
    public $customer_details;
    public $agb_and_gdpr;

    private $_payment;

    private $_order;

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->_order;
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            [
                'first_name',
                'surname',
                'email',
                'street_name',
                'house_number',
                'postal',
                'city',
            ],
            'required'
        ];
        $rules[] = [
            [
                'has_different_delivery_address',
                'delivery_first_name',
                'delivery_surname',
                'delivery_street_name',
                'delivery_house_number',
                'delivery_postal',
                'delivery_city',
            ],
            'safe'
        ];

        $rules[] = [
            [
                'delivery_first_name',
                'delivery_surname',
                'delivery_street_name',
                'delivery_house_number',
                'delivery_postal',
                'delivery_city',
            ],
            DependencyValidator::class,
            'model' => $this,
            'dependent_attribute' => 'has_different_delivery_address'
        ];
        $rules[] = [
            'agb_and_gdpr',
            'required',
            'requiredValue' => 1,
            'message' => Yii::t('shop', 'Sie müssen zustimmen um fortzufahren.')
        ];
        $rules[] = [
            'email',
            'email'
        ];
        $rules[] = ['type', 'in', 'range' => array_keys(Order::optsType())
        ];
        $rules[] = [
            'customer_details',
            'string',
            'max' => 250
        ];
        if (ShopSettings::shopGeneralShopSellsAdultProducts()) {
            $rules[] = [
                'date_of_birth',
                'required'
            ];
            $rules[] = [
                'date_of_birth',
                'validateBirthday'
            ];
            $rules[] = [
                'date_of_birth',
                'date',
                'format' => 'php:d.m.Y',
            ];
        } else {
            $rules[] = [
                'date_of_birth',
                'safe'
            ];
        }
        return $rules;
    }

    public function validateBirthday()
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval('P18Y'));
        $min = $date->getTimestamp();
        $dateOfBirth = strtotime($this->date_of_birth);

        if ($dateOfBirth > $min) {
            $this->addError('date_of_birth', Yii::t('shop', 'You must be 18 years old or older to continue'));
        }
    }

    public $_exception;

    public function beforeValidate()
    {
        if (!Yii::$app->shoppingCart->hasReachedMinValue()) {
            $this->addError('_exception', Yii::t('shop',
                'In order to continue you must reach the minimum shopping cart limit of {value}', [
                    'value' => Yii::$app->formatter->asCurrency(ShopSettings::shopGeneralMinShoppingCartValue(),
                        Yii::$app->payment->currency)
                ]));
        }

        if (ShopSettings::shopGeneralShopSellsAdultProducts()) {
            $this->date_of_birth = date('d.m.Y', strtotime($this->date_of_birth));
        }
        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['first_name'] = Yii::t('shop', 'First Name');
        $attributeLabels['surname'] = Yii::t('shop', 'Surname');
        $attributeLabels['email'] = Yii::t('shop', 'Email');
        $attributeLabels['street_name'] = Yii::t('shop', 'Street Name');
        $attributeLabels['house_number'] = Yii::t('shop', 'House Number');
        $attributeLabels['postal'] = Yii::t('shop', 'Postal');
        $attributeLabels['city'] = Yii::t('shop', 'City');
        $attributeLabels['discount_code'] = Yii::t('shop', 'Discount Code');
        $attributeLabels['type'] = Yii::t('shop', 'Payment Type');
        $attributeLabels['agb_and_gdpr'] = Yii::t('shop', 'AGBs');
        $attributeLabels['date_of_birth'] = Yii::t('shop', 'Date of birth');
        $attributeLabels['delivery_first_name'] = Yii::t('shop', 'Delivery First Name');
        $attributeLabels['delivery_surname'] = Yii::t('shop', 'Delivery Surname');
        $attributeLabels['delivery_street_name'] = Yii::t('shop', 'Delivery Street Name');
        $attributeLabels['delivery_house_number'] = Yii::t('shop', 'Delivery House Number');
        $attributeLabels['delivery_postal'] = Yii::t('shop', 'Delivery Postal');
        $attributeLabels['delivery_city'] = Yii::t('shop', 'Delivery City');
        $attributeLabels['customer_details'] = Yii::t('shop', 'Customer Details');
        return $attributeLabels;
    }

    public function attributeHints()
    {
        $attributeHints = parent::attributeHints();
        $attributeHints['agb_and_gdpr'] = Yii::t('shop', '__PLACEHOLDER_AGBS_AND_DSGVO__');
        $attributeHints['has_different_delivery_address'] = Yii::t('shop', 'Abweichende Lieferadresse');
        return $attributeHints;
    }

    public function approvalLink()
    {
        if ($this->_payment) {
            return $this->_payment->getApprovalLink();
        }
        return false;
    }

    public function checkout()
    {
        if (!$this->validate() || Yii::$app->shoppingCart->isEmpty) {
            Yii::error($this->errors, __METHOD__);
            return false;
        }

        $orderId = Order::generateId();

        if ($this->type !== Order::TYPE_PREPAYMENT) {

            $this->_payment = \Yii::$app->shoppingCart->checkout($orderId);
        } else {
            $this->_payment = true;
        }

        if ($this->_payment) {
            $transaction = Yii::$app->db->beginTransaction();
            $config = [
                'id' => $orderId,
                'type' => $this->type,
                'first_name' => $this->first_name,
                'surname' => $this->surname,
                'email' => $this->email,
                'street_name' => $this->street_name,
                'house_number' => $this->house_number,
                'postal' => $this->postal,
                'city' => $this->city,
                'has_different_delivery_address' => $this->has_different_delivery_address,
                'delivery_first_name' => $this->delivery_first_name,
                'delivery_surname' => $this->delivery_surname,
                'delivery_street_name' => $this->delivery_street_name,
                'delivery_house_number' => $this->delivery_house_number,
                'delivery_postal' => $this->delivery_postal,
                'delivery_city' => $this->delivery_city,
                'discount_code_id' => $this->getDiscountCodeId(),
                'shipping_price' => Yii::$app->shoppingCart->shippingCost(),
                'status' => Order::STATUS_PENDING,
                'customer_details' => $this->customer_details
            ];

            if (ShopSettings::shopGeneralAllowCustomerDetails()) {
                $config['customer_details'] = $this->customer_details;
            }

            if (ShopSettings::shopGeneralShopSellsAdultProducts()) {
                $config['date_of_birth'] = date('Y-m-d', strtotime($this->date_of_birth));
            }

            if ($this->type === Order::TYPE_PAYPAL) {
                $config['paypal_id'] = $this->_payment->getId();
            }

            $order = new Order($config);

            if (!$order->save()) {
                $transaction->rollBack();
                Yii::error('Error while saving order', __METHOD__);
                Yii::error($order->errors, __METHOD__);
                return false;
            }

            $this->_order = $order;

            foreach (Yii::$app->shoppingCart->positions as $position) {
                $item = $position->item();
                if ($item) {
                    if ($position->isDiscount === false) {
                        $name = $item->product->title . ' - ' . $item->title;
                    } else {
                        $name = $item->label;
                    }
                    $orderItem = new OrderItem([
                        'order_id' => $order->id,
                        'variant_id' => $position->itemId,
                        'name' => $name,
                        'quantity' => $position->quantity,
                        'single_price' => $position->price,
                        'extra_info' => $position->extraInfo ?: '-'
                    ]);

                    if (!$orderItem->checkout($position->isDiscount)) {
                        $transaction->rollBack();
                        Yii::error('Error while order item checkout', __METHOD__);
                        return false;
                    }
                } else {
                    $transaction->rollBack();
                    Yii::error('Item does not exist', __METHOD__);
                    return false;
                }
            }
            $transaction->commit();
            return true;
        }
        Yii::error('Error while performing shopping cart checkout', __METHOD__);
        return false;
    }

    public function getDiscountCodeId()
    {
        /** @var DiscountCode|null $model */
        $model = Yii::$app->shoppingCart->getDiscountCode();
        if ($model) {
            return $model->id;
        }
        return null;
    }
}
