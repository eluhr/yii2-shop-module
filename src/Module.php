<?php

namespace eluhr\shop;

use eluhr\shop\assets\ShopFrontendAsset;
use eluhr\shop\models\Order;
use yii\base\Module as BaseModule;
use yii\swiftmailer\Mailer;

class Module extends BaseModule
{
    public $frontendLayout = '@app/views/layouts/main';
    public $backendLayout = '@app/views/layouts/main';

    public const SHOP_EDITOR_ROLE = 'ShopEditor';

    /**
     * if set we add an access check with given rules in the base frontend controller
     * - the given array MUST be an array of valid AccessRule(s)
     * - if defined as callback, the 1. param is the current controller instance
     *
     * @var callable|array
     */
    public $frontendAccessRules;

    /**
     * @var Mailer
     */
    public $mailer = 'mailer';

    //  3-letter ISO 4217 currency code indicating the currency to use.
    public $currency = 'EUR';

    public $allowedPaymentMethods = [
        Order::TYPE_PREPAYMENT
    ];

    /**
     * Callback to modify the variant thumbnail. Callback must return a string
     *
     * @var callable|null
     */
    public $variantThumbnailImageCallback;

    /**
     * @inheritdoc
    */
    public function beforeAction($action)
    {
        ShopFrontendAsset::register(\Yii::$app->controller->view);
        return parent::beforeAction($action);
    }
}
