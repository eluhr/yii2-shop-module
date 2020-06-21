<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\models;

use yii\base\Model;
use yii\helpers\VarDumper;

class ShopSettings extends Model
{
    public const SHOP_GENERAL_SHOW_FILTERS = 'shopGeneralShowFilters';
    public const SHOP_GENERAL_INVOICE_DOWNLOAD = 'shopGeneralInvoiceDownload';
    public const SHOP_GENERAL_SHIPPING_LINK = 'shopGeneralShippingLink';
    public const SHOP_GENERAL_ENABLE_DISCOUNT_CODES = 'shopGeneralEnableDiscountCodes';
    public const SHOP_MAIL_CONFIRM_REPLY_TO = 'shopMailConfirmReplyTo';
    public const SHOP_MAIL_INFO_REPLY_TO = 'shopMailInfoReplyTo';
    public const SHOP_PRODUCT_FEW_AVAILABLE_WARNING = 'shopProductFewAvailableWarning';
    public const SHOP_MAIL_INFO_SUBJECT = 'shopMailInfoSubject';
    public const SHOP_MAIL_CONFIRM_SUBJECT = 'shopMailConfirmSubject';
    public const SHOP_MAIL_CONFIRM_BCC = 'shopMailConfirmBcc';
    public const SHOP_MAIL_LOGO = 'shopMailLogo';
    public const SHOP_INVOICE_LOGO = 'shopInvoiceLogo';
    public const SHOP_GENERAL_SHORT_ORDER_ID = 'shopGeneralShortOrderId';
    public const SHOP_GENERAL_SHOW_OUT_OF_STOCK_VARIANTS = 'shopGeneralShowOutOfStockVariants';
    public const SHOP_GENERAL_SHOP_SELLS_ADULT_PRODUCTS = 'shopGeneralShopSellsAdultProducts';
    public $shopGeneralShowFilters;
    public $shopGeneralInvoiceDownload;
    public $shopGeneralShippingLink;
    public $shopGeneralEnableDiscountCodes;
    public $shopMailConfirmReplyTo;
    public $shopMailInfoReplyTo;
    public $shopProductFewAvailableWarning;
    public $shopMailInfoSubject;
    public $shopMailConfirmSubject;
    public $shopMailConfirmBcc;
    public $shopMailLogo;
    public $shopInvoiceLogo;
    public $shopGeneralShortOrderId;
    public $shopGeneralShowOutOfStockVariants;
    public $shopGeneralShopSellsAdultProducts;


    protected static $settings = [
        self::SHOP_GENERAL_SHOW_FILTERS => [
            'type' => 'bool',
            'default' => true
        ],
        self::SHOP_GENERAL_INVOICE_DOWNLOAD => [
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
        ]
    ];

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            [
                self::SHOP_GENERAL_SHOW_FILTERS,
                self::SHOP_GENERAL_INVOICE_DOWNLOAD,
                self::SHOP_GENERAL_SHIPPING_LINK,
                self::SHOP_GENERAL_SHORT_ORDER_ID,
                self::SHOP_GENERAL_SHOW_OUT_OF_STOCK_VARIANTS,
                self::SHOP_GENERAL_SHOP_SELLS_ADULT_PRODUCTS
            ],
            'safe'
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
                self::SHOP_MAIL_INFO_SUBJECT,
                self::SHOP_MAIL_CONFIRM_SUBJECT
            ],
            'string',
            'min' => 1,
        ];
        $rules[] = [
            [
                self::SHOP_MAIL_LOGO,
                self::SHOP_INVOICE_LOGO
            ],
            'url'
        ];

        $rules[] = [
            [
                self::SHOP_MAIL_LOGO,
                self::SHOP_INVOICE_LOGO
            ],
            'checkIfDownloadableImage'
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

    public function checkIfDownloadableImage($attribute)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->$attribute);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        if (strpos($output, ' image/') === false) {
            $this->addError($attribute, \Yii::t('shop', 'Url must be a reachable image url'));
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

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels[self::SHOP_GENERAL_SHOW_FILTERS] = \Yii::t('shop', 'Show filters');
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
        $attributeLabels[self::SHOP_INVOICE_LOGO] = \Yii::t('shop', 'Invoice Logo');
        $attributeLabels[self::SHOP_GENERAL_SHORT_ORDER_ID] = \Yii::t('shop', 'Enable Short Order Numbers');
        $attributeLabels[self::SHOP_GENERAL_SHOW_OUT_OF_STOCK_VARIANTS] = \Yii::t('shop', 'Show out of stock variants');
        $attributeLabels[self::SHOP_GENERAL_SHOP_SELLS_ADULT_PRODUCTS] = \Yii::t('shop', 'Show sells adult products');
        return $attributeLabels;
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
                return false;
            }
        }
        $transaction->commit();
        return true;
    }
}
