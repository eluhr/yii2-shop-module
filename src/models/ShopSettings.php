<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\models;

use Yii;
use yii\base\Model;

class ShopSettings extends Model
{
    public const SHOP_GENERAL_SHOW_FILTERS = 'shopGeneralShowFilters';
    public const SHOP_GENERAL_SHOW_SEARCH = 'shopGeneralShowSearch';
    public const SHOP_GENERAL_INVOICE_DOWNLOAD = 'shopGeneralInvoiceDownload';
    public const SHOP_GENERAL_SHIPPING_LINK = 'shopGeneralShippingLink';
    public const SHOP_GENERAL_ENABLE_DISCOUNT_CODES = 'shopGeneralEnableDiscountCodes';
    public const SHOP_MAIL_CONFIRM_REPLY_TO = 'shopMailConfirmReplyTo';
    public const SHOP_MAIL_INFO_REPLY_TO = 'shopMailInfoReplyTo';
    public const SHOP_PRODUCT_FEW_AVAILABLE_WARNING = 'shopProductFewAvailableWarning';
    public const SHOP_PRODUCT_SHOW_SHIPPING_COSTS = 'shopProductShowShippingCosts';
    public const SHOP_PRODUCT_MIN_DAYS_SHIPPING_DURATION = 'shopProductMinDaysShippingDuration';
    public const SHOP_PRODUCT_MAX_DAYS_SHIPPING_DURATION = 'shopProductMaxDaysShippingDuration';
    public const SHOP_PRODUCT_ENABLE_SHIPPING_DURATION = 'shopProductEnableShippingDuration';
    public const SHOP_PRODUCT_ALLOW_CONFIGURABLE_VARIANT = 'shopProductAllowConfigurableVariant';
    public const SHOP_MAIL_INFO_SUBJECT = 'shopMailInfoSubject';
    public const SHOP_MAIL_CONFIRM_SUBJECT = 'shopMailConfirmSubject';
    public const SHOP_MAIL_CONFIRM_BCC = 'shopMailConfirmBcc';
    public const SHOP_MAIL_LOGO = 'shopMailLogo';
    public const SHOP_INVOICE_LOGO = 'shopInvoiceLogo';
    public const SHOP_GENERAL_SHORT_ORDER_ID = 'shopGeneralShortOrderId';
    public const SHOP_GENERAL_SHOW_OUT_OF_STOCK_VARIANTS = 'shopGeneralShowOutOfStockVariants';
    public const SHOP_GENERAL_SHOP_SELLS_ADULT_PRODUCTS = 'shopGeneralShopSellsAdultProducts';
    public const SHOP_GENERAL_MIN_SHOPPING_CART_VALUE = 'shopGeneralMinShoppingCartValue';
    public const SHOP_PRODUCT_VARIANT_TEXT_TEMPLATE = 'shopProductVariantTextTemplate';
    public const SHOP_PRODUCT_SHOW_VAT = 'shopProductShowVat';
    public const SHOP_GENERAL_ALLOW_CUSTOMER_DETAILS = 'shopGeneralAllowCustomerDetails';
    public const SHOP_PRODUCT_DEFAULT_VAT = 'shopProductDefaultVat';
    public const SHOP_MAIL_SHOW_BANK_DETAILS = 'shopMailShowBankDetails';
    public const SHOP_CHECKOUT_PAYMENT_PROVIDERS = 'shopCheckoutPaymentProviders';
    public $shopMailShowBankDetails;
    public $shopProductDefaultVat;
    public $shopGeneralShowFilters;
    public $shopGeneralShowSearch;
    public $shopGeneralInvoiceDownload;
    public $shopGeneralShippingLink;
    public $shopGeneralEnableDiscountCodes;
    public $shopMailConfirmReplyTo;
    public $shopMailInfoReplyTo;
    public $shopProductFewAvailableWarning;
    public $shopProductShowShippingCosts;
    public $shopProductAllowConfigurableVariant;
    public $shopMailInfoSubject;
    public $shopMailConfirmSubject;
    public $shopMailConfirmBcc;
    public $shopMailLogo;
    public $shopInvoiceLogo;
    public $shopGeneralShortOrderId;
    public $shopGeneralShowOutOfStockVariants;
    public $shopGeneralShopSellsAdultProducts;
    public $shopGeneralMinShoppingCartValue;
    public $shopProductMinDaysShippingDuration;
    public $shopProductMaxDaysShippingDuration;
    public $shopProductVariantTextTemplate;
    public $shopGeneralAllowCustomerDetails;
    public $shopProductShowVat;
    public $shopCheckoutPaymentProviders;
    public $shopProductEnableShippingDuration;


    protected static $settings = [
        self::SHOP_GENERAL_SHOW_FILTERS => [
            'type' => 'bool',
            'default' => true
        ],
        self::SHOP_GENERAL_SHOW_SEARCH => [
            'type' => 'bool',
            'default' => true
        ],
        self::SHOP_PRODUCT_SHOW_VAT => [
            'type' => 'bool',
            'default' => false
        ],
        self::SHOP_GENERAL_INVOICE_DOWNLOAD => [
            'type' => 'bool',
            'default' => true
        ],
        self::SHOP_MAIL_SHOW_BANK_DETAILS => [
            'type' => 'bool',
            'default' => true
        ],
        self::SHOP_GENERAL_SHIPPING_LINK => [
            'type' => 'bool',
            'default' => true
        ],
        self::SHOP_GENERAL_ENABLE_DISCOUNT_CODES => [
            'type' => 'bool',
            'default' => true
        ],
        self::SHOP_PRODUCT_ENABLE_SHIPPING_DURATION => [
            'type' => 'bool',
            'default' => true
        ],
        self::SHOP_GENERAL_ALLOW_CUSTOMER_DETAILS => [
            'type' => 'bool',
            'default' => false
        ],
        self::SHOP_PRODUCT_ALLOW_CONFIGURABLE_VARIANT => [
            'type' => 'bool',
            'default' => false
        ],
        self::SHOP_MAIL_CONFIRM_REPLY_TO => [
            'type' => 'string',
            'default' => 'info@example.com'
        ],
        self::SHOP_MAIL_INFO_REPLY_TO => [
            'type' => 'string',
            'default' => 'info@example.com'
        ],
        self::SHOP_PRODUCT_FEW_AVAILABLE_WARNING => [
            'type' => 'int',
            'default' => 15
        ],
        self::SHOP_PRODUCT_MIN_DAYS_SHIPPING_DURATION => [
            'type' => 'int',
            'default' => 5
        ],
        self::SHOP_PRODUCT_MAX_DAYS_SHIPPING_DURATION => [
            'type' => 'int',
            'default' => 10
        ],
        self::SHOP_MAIL_INFO_SUBJECT => [
            'type' => 'string',
            'default' => 'Bestellung wurde versendet'
        ],
        self::SHOP_MAIL_CONFIRM_SUBJECT => [
            'type' => 'string',
            'default' => 'Bestellbestätigung'
        ],
        self::SHOP_MAIL_CONFIRM_BCC => [
            'type' => 'string',
            'default' => 'info@example.com'
        ],
        self::SHOP_MAIL_LOGO => [
            'type' => 'string',
            'default' => ''
        ],
        self::SHOP_INVOICE_LOGO => [
            'type' => 'string',
            'default' => ''
        ],
        self::SHOP_PRODUCT_VARIANT_TEXT_TEMPLATE => [
            'type' => 'string',
            'default' => ''
        ],
        self::SHOP_GENERAL_SHORT_ORDER_ID => [
            'type' => 'bool',
            'default' => false
        ],
        self::SHOP_GENERAL_SHOW_OUT_OF_STOCK_VARIANTS => [
            'type' => 'bool',
            'default' => true
        ],
        self::SHOP_GENERAL_SHOP_SELLS_ADULT_PRODUCTS => [
            'type' => 'bool',
            'default' => false
        ],
        self::SHOP_PRODUCT_SHOW_SHIPPING_COSTS => [
            'type' => 'bool',
            'default' => true
        ],
        self::SHOP_GENERAL_MIN_SHOPPING_CART_VALUE => [
            'type' => 'float',
            'default' => 0.00
        ],
        self::SHOP_PRODUCT_DEFAULT_VAT => [
            'type' => 'float',
            'default' => 0.00
        ],
        self::SHOP_CHECKOUT_PAYMENT_PROVIDERS => [
            'type' => 'string',
            'default' => '_default_'
        ]
    ];

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            [
                self::SHOP_GENERAL_SHOW_FILTERS,
                self::SHOP_GENERAL_SHOW_SEARCH,
                self::SHOP_PRODUCT_ENABLE_SHIPPING_DURATION,
                self::SHOP_GENERAL_INVOICE_DOWNLOAD,
                self::SHOP_GENERAL_SHIPPING_LINK,
                self::SHOP_GENERAL_SHORT_ORDER_ID,
                self::SHOP_GENERAL_SHOW_OUT_OF_STOCK_VARIANTS,
                self::SHOP_GENERAL_SHOP_SELLS_ADULT_PRODUCTS,
                self::SHOP_PRODUCT_SHOW_SHIPPING_COSTS,
                self::SHOP_PRODUCT_VARIANT_TEXT_TEMPLATE,
                self::SHOP_GENERAL_ALLOW_CUSTOMER_DETAILS,
                self::SHOP_PRODUCT_SHOW_VAT,
                self::SHOP_MAIL_SHOW_BANK_DETAILS,
                self::SHOP_MAIL_LOGO,
                self::SHOP_INVOICE_LOGO,
                self::SHOP_CHECKOUT_PAYMENT_PROVIDERS,
                self::SHOP_PRODUCT_ALLOW_CONFIGURABLE_VARIANT,
            ],
            'safe'
        ];
        $rules[] = [
            [
                self::SHOP_PRODUCT_SHOW_VAT,
            ],
            'boolean'
        ];
        $rules[] = [
            [
                self::SHOP_MAIL_CONFIRM_REPLY_TO,
                self::SHOP_MAIL_INFO_REPLY_TO
            ],
            'email'
        ];
        $rules[] = [
            [
                self::SHOP_PRODUCT_FEW_AVAILABLE_WARNING
            ],
            'integer',
            'min' => 1,
        ];
        $rules[] = [
            [
                self::SHOP_GENERAL_MIN_SHOPPING_CART_VALUE,
                self::SHOP_PRODUCT_MIN_DAYS_SHIPPING_DURATION,
                self::SHOP_PRODUCT_MAX_DAYS_SHIPPING_DURATION
            ],
            'number',
            'min' => 0,
            'max' => 9999999
        ];
        $rules[] = [
               self::SHOP_PRODUCT_DEFAULT_VAT,
            'number',
            'min' => 0,
            'max' => 100
        ];
        $rules[] = [
            [
                self::SHOP_MAIL_INFO_SUBJECT,
                self::SHOP_MAIL_CONFIRM_SUBJECT
            ],
            'string',
            'min' => 1,
        ];
        $rules[] = [
            self::SHOP_PRODUCT_VARIANT_TEXT_TEMPLATE,
            'string',
            'max' => 255,
        ];
        $rules[] = [
            self::SHOP_MAIL_CONFIRM_BCC,
            'confirmBccCheck'
        ];
        return $rules;
    }

    public function confirmBccCheck()
    {
        $attribute = self::SHOP_MAIL_CONFIRM_BCC;
        foreach (explode(',', $this->$attribute) as $email) {
            if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) === false) {
                $this->addError($attribute, \Yii::t('shop', 'Items must be a valid email'));
                break;
            }
        }
    }

    public function init()
    {
        foreach (self::$settings as $attribute => $data) {
            $this->$attribute = self::getValueByConst($attribute);
        }
        parent::init();
    }

    protected static function getValueByConst($const)
    {
        $data = self::$settings[$const];
        $model = Setting::findOne(['key' => $const]);
        if ($model) {
            $value = $model->value;
        } else {
            $value = $data['default'];
        }
        settype($value, $data['type']);
        return $value;
    }

    public static function shopGeneralShowFilters(): bool
    {
        return static::getValueByConst(self::SHOP_GENERAL_SHOW_FILTERS);
    }

    public static function shopGeneralShowSearch(): bool
    {
        return static::getValueByConst(self::SHOP_GENERAL_SHOW_SEARCH);
    }

    public static function shopGeneralInvoiceDownload(): bool
    {
        return static::getValueByConst(self::SHOP_GENERAL_INVOICE_DOWNLOAD);
    }

    public static function shopGeneralShippingLink(): bool
    {
        return static::getValueByConst(self::SHOP_GENERAL_SHIPPING_LINK);
    }

    public static function shopGeneralEnableDiscountCodes(): bool
    {
        return static::getValueByConst(self::SHOP_GENERAL_ENABLE_DISCOUNT_CODES);
    }

    public static function shopMailConfirmReplyTo(): string
    {
        return static::getValueByConst(self::SHOP_MAIL_CONFIRM_REPLY_TO);
    }

    public static function shopMailInfoReplyTo(): string
    {
        return static::getValueByConst(self::SHOP_MAIL_INFO_REPLY_TO);
    }

    public static function shopProductFewAvailableWarning(): int
    {
        return static::getValueByConst(self::SHOP_PRODUCT_FEW_AVAILABLE_WARNING);
    }

    public static function shopMailInfoSubject(): string
    {
        return static::getValueByConst(self::SHOP_MAIL_INFO_SUBJECT);
    }

    public static function shopMailConfirmSubject(): string
    {
        return static::getValueByConst(self::SHOP_MAIL_CONFIRM_SUBJECT);
    }

    public static function shopMailConfirmBcc(): string
    {
        return static::getValueByConst(self::SHOP_MAIL_CONFIRM_BCC);
    }

    public static function shopMailLogo(): string
    {
        return static::getValueByConst(self::SHOP_MAIL_LOGO);
    }

    public static function shopInvoiceLogo(): string
    {
        return static::getValueByConst(self::SHOP_INVOICE_LOGO);
    }

    public static function shopGeneralShortOrderId(): bool
    {
        return static::getValueByConst(self::SHOP_GENERAL_SHORT_ORDER_ID);
    }

    public static function shopGeneralShowOutOfStockVariants(): bool
    {
        return static::getValueByConst(self::SHOP_GENERAL_SHOW_OUT_OF_STOCK_VARIANTS);
    }

    public static function shopGeneralShopSellsAdultProducts(): bool
    {
        return static::getValueByConst(self::SHOP_GENERAL_SHOP_SELLS_ADULT_PRODUCTS);
    }

    public static function shopGeneralMinShoppingCartValue(): float
    {
        return static::getValueByConst(self::SHOP_GENERAL_MIN_SHOPPING_CART_VALUE);
    }

    public static function shopProductShowShippingCosts(): bool
    {
        return static::getValueByConst(self::SHOP_PRODUCT_SHOW_SHIPPING_COSTS);
    }

    public static function shopProductMinDaysShippingDuration(): int
    {
        return static::getValueByConst(self::SHOP_PRODUCT_MIN_DAYS_SHIPPING_DURATION);
    }

    public static function shopProductMaxDaysShippingDuration(): int
    {
        return static::getValueByConst(self::SHOP_PRODUCT_MAX_DAYS_SHIPPING_DURATION);
    }

    public static function shopProductVariantTextTemplate(): string
    {
        return static::getValueByConst(self::SHOP_PRODUCT_VARIANT_TEXT_TEMPLATE);
    }

    public static function shopGeneralAllowCustomerDetails(): string
    {
        return static::getValueByConst(self::SHOP_GENERAL_ALLOW_CUSTOMER_DETAILS);
    }

    public static function shopProductShowVat(): bool
    {
        return static::getValueByConst(self::SHOP_PRODUCT_SHOW_VAT);
    }

    public static function shopProductDefaultVat(): float
    {
        return static::getValueByConst(self::SHOP_PRODUCT_DEFAULT_VAT);
    }

    public static function shopMailShowBankDetails(): bool
    {
        return static::getValueByConst(self::SHOP_MAIL_SHOW_BANK_DETAILS);
    }

    public static function shopCheckoutPaymentProviders(): string
    {
        return static::getValueByConst(self::SHOP_CHECKOUT_PAYMENT_PROVIDERS);
    }

    public static function shopProductEnableShippingDuration(): bool
    {
        return static::getValueByConst(self::SHOP_PRODUCT_ENABLE_SHIPPING_DURATION);
    }

    public static function shopProductAllowConfigurableVariant(): bool
    {
        return static::getValueByConst(self::SHOP_PRODUCT_ALLOW_CONFIGURABLE_VARIANT);
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels[self::SHOP_GENERAL_SHOW_FILTERS] = \Yii::t('shop', 'Show filters');
        $attributeLabels[self::SHOP_GENERAL_SHOW_SEARCH] = \Yii::t('shop', 'Show Search field');
        $attributeLabels[self::SHOP_GENERAL_SHIPPING_LINK] = \Yii::t('shop', 'Show Shipping Link');
        $attributeLabels[self::SHOP_GENERAL_INVOICE_DOWNLOAD] = \Yii::t('shop', 'Show Invoice Download');
        $attributeLabels[self::SHOP_GENERAL_ENABLE_DISCOUNT_CODES] = \Yii::t('shop', 'Enable Discount Codes');
        $attributeLabels[self::SHOP_MAIL_CONFIRM_REPLY_TO] = \Yii::t('shop', 'Confirm Reply To');
        $attributeLabels[self::SHOP_MAIL_INFO_REPLY_TO] = \Yii::t('shop', 'Info Reply To');
        $attributeLabels[self::SHOP_PRODUCT_FEW_AVAILABLE_WARNING] = \Yii::t('shop', 'Few Available Warning');
        $attributeLabels[self::SHOP_MAIL_INFO_SUBJECT] = \Yii::t('shop', 'Info Mail Subject');
        $attributeLabels[self::SHOP_MAIL_CONFIRM_SUBJECT] = \Yii::t('shop', 'Confirm Mail Subject');
        $attributeLabels[self::SHOP_MAIL_CONFIRM_BCC] = \Yii::t('shop', 'Confirm Mail Bcc');
        $attributeLabels[self::SHOP_MAIL_LOGO] = \Yii::t('shop', 'Mail Logo');
        $attributeLabels[self::SHOP_MAIL_SHOW_BANK_DETAILS] = \Yii::t('shop', 'Mail Show Bank details');
        $attributeLabels[self::SHOP_INVOICE_LOGO] = \Yii::t('shop', 'Invoice Logo');
        $attributeLabels[self::SHOP_GENERAL_SHORT_ORDER_ID] = \Yii::t('shop', 'Enable Short Order Numbers');
        $attributeLabels[self::SHOP_GENERAL_SHOW_OUT_OF_STOCK_VARIANTS] = \Yii::t('shop', 'Show out of stock variants');
        $attributeLabels[self::SHOP_GENERAL_SHOP_SELLS_ADULT_PRODUCTS] = \Yii::t('shop', 'Show sells adult products');
        $attributeLabels[self::SHOP_GENERAL_MIN_SHOPPING_CART_VALUE] = \Yii::t('shop', 'Min shopping cart value');
        $attributeLabels[self::SHOP_PRODUCT_SHOW_SHIPPING_COSTS] = \Yii::t('shop', 'Show shipping costs');
        $attributeLabels[self::SHOP_PRODUCT_MIN_DAYS_SHIPPING_DURATION] = \Yii::t('shop', 'Min days shipping duration');
        $attributeLabels[self::SHOP_PRODUCT_MAX_DAYS_SHIPPING_DURATION] = \Yii::t('shop', 'Max days shipping duration');
        $attributeLabels[self::SHOP_PRODUCT_VARIANT_TEXT_TEMPLATE] = \Yii::t('shop', 'Variant text template');
        $attributeLabels[self::SHOP_GENERAL_ALLOW_CUSTOMER_DETAILS] = \Yii::t('shop', 'Show customer details text box in checkout');
        $attributeLabels[self::SHOP_PRODUCT_SHOW_VAT] = \Yii::t('shop', 'Show VAT in product variants');
        $attributeLabels[self::SHOP_PRODUCT_DEFAULT_VAT] = \Yii::t('shop', 'Default Variant VAT for new products');
        return $attributeLabels;
    }

    public function attributeHints()
    {
        $attributeHints = parent::attributeHints();
        $attributeHints[self::SHOP_GENERAL_MIN_SHOPPING_CART_VALUE] = \Yii::t('shop','If set to 0 (zero), there is no minimum limit');
        return $attributeHints;
    }

    public function updateData($data)
    {
        if (!isset($data[$this->formName()])) {
            return false;
        }
        $attributes = $data[$this->formName()];

        $transaction = \Yii::$app->db->beginTransaction();
        if ($transaction === null) {
            return false;
        }
        foreach ($attributes as $attribute => $value) {
            $this->$attribute = $value;
            if ($this->validate() === false) {
                $transaction->rollBack();
                Yii::error($this->getErrors());
                return false;
            }
            $model = Setting::findOne(['key' => $attribute]);
            if ($model === null) {
                $model = new Setting([
                    'key' =>$attribute,
                ]);
            }
            $model->value = $this->$attribute;
            if (!$model->save()) {
                $transaction->rollBack();
                Yii::error([$attribute, $model->getErrors()]);
                return false;
            }
        }
        $transaction->commit();
        return true;
    }

    /**
     * @return array
     */
    public static function allPaymentProviders(): array
    {
        $installedProviders = Yii::$app->payment->providers;
        $providers = [];
        foreach ($installedProviders as $name => $config) {
            $providers[$name] = Order::getTypeValueLabel($name);
        }
        return $providers;
    }

    public static function allowedPaymentProviders(): array {
        $providers = self::shopCheckoutPaymentProviders();
        if ($providers === '_default_') {
            $_list = array_keys(Yii::$app->payment->providers);
        } else {
            $_list = explode(',', $providers);
        }
        $list = [];
        foreach ($_list as $item) {
            if (isset(Order::optsType()[$item])) {
                $list[$item] = Order::optsType()[$item];
            }
        }
        return $list;
    }
}
