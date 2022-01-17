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

    /**
     * @var Mailer
     */
    public $mailer = 'mailer';

    //  3-letter ISO 4217 currency code indicating the currency to use.
    public $currency = 'EUR';

    public $allowedPaymentMethods = [
        Order::TYPE_PREPAYMENT
    ];

    public function beforeAction($action)
    {
        ShopFrontendAsset::register(\Yii::$app->controller->view);
        return parent::beforeAction($action);
    }
}
