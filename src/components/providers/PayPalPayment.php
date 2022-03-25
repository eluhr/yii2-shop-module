<?php

namespace eluhr\shop\components\providers;

use eluhr\shop\components\traits\ApprovalLink;
use eluhr\shop\interfaces\ExternalPaymentProvider;
use eluhr\shop\models\Order;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 */
class PayPalPayment extends BasePaymentProvider implements ExternalPaymentProvider
{

    use ApprovalLink;

    public $mode;
    public $clientId;
    public $clientSecret;
    public $logDirectory = '@runtime/paypal-logs';
    public $returnUrl = ['/shop/shopping-cart/success-external'];
    public $cancelUrl = ['/shop/shopping-cart/canceled'];

    protected $_paypal;
    protected $_paypalPayment;
    protected $_total = 0;

    public function init()
    {
        parent::init();

        if (!defined('PP_CONFIG_PATH')) {
            define('PP_CONFIG_PATH', __DIR__);
        }

        $this->_paypal = new ApiContext(
            new OAuthTokenCredential(
                $this->clientId,
                $this->clientSecret
            )
        );

        $logPath = \Yii::getAlias($this->logDirectory);
        $this->_paypal->setConfig(
            [
                'mode' => $this->mode,
                'http.ConnectionTimeOut' => 30,
                'http.Retry' => 1,
                'log.LogEnabled' => YII_DEBUG,
                'log.FileName' => $logPath . '/paypal.log',
                'cache.FileName' => $logPath . '/auth.cache',
                'log.LogLevel' => 'DEBUG',
                'validation.level' => 'log',
                'cache.enabled' => 'true'
            ]
        );

        FileHelper::createDirectory($logPath);

        if (!is_file($this->_paypal->getConfig()['log.FileName']) && !touch($this->_paypal->getConfig()['log.FileName'])) {
            throw new \ErrorException(\Yii::t('shop', 'Error while creating log file'));
        }
        if (!is_file($this->_paypal->getConfig()['cache.FileName']) && !touch($this->_paypal->getConfig()['cache.FileName'])) {
            throw new \ErrorException(\Yii::t('shop', 'Error while creating auth cache file'));
        }
    }

    public function addItem(array $itemData): void
    {
        $item = new Item();
        $item->setName($itemData['name']);
        $item->setCurrency($this->_currency);
        if (!empty($itemData['sku'])) {
            $item->setSku($itemData['sku']);
        } else {
            $item->setSku(Inflector::slug($itemData['name']));
        }
        $item->setQuantity($itemData['quantity']);
        $item->setPrice($itemData['price']);

        $this->_total += $item->getPrice() * $item->getQuantity();
        $this->_items[] = $item;
    }

    /**
     * @return int
     */
    public function getTotalPrice()
    {
        return $this->_total;
    }

    public function execute(): bool
    {
        if (!empty($this->_items)) {
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $item = new Item();
            $item->setName(\Yii::t('shop', 'Shipping cost', [], 'de'))
                ->setCurrency($this->_currency)
                ->setQuantity(1)
                ->setPrice($this->_shippingCost);

            $this->_total += $this->_shippingCost;
            $this->_items[] = $item;

            $itemList = new ItemList();
            $itemList->setItems($this->_items);

            $amount = new Amount();
            $amount->setCurrency($this->_currency)
                ->setTotal((float)$this->getTotalPrice());

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription(\Yii::t('shop', '__PAYPAL_TRANSACTION_DESCRIPTION__'))
                ->setInvoiceNumber(uniqid('', false));

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(Url::to($this->returnUrl, 'http' . (YII_ENV_DEV ? '' : 's')))
                ->setCancelUrl(Url::to($this->cancelUrl, 'http' . (YII_ENV_DEV ? '' : 's')));

            $this->_paypalPayment = new Payment();
            $this->_paypalPayment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

            try {
                $this->_paypalPayment->create($this->_paypal);
            } catch (\Exception $e) {
                \Yii::error($e->getMessage(), __METHOD__);
                return false;
            }
            $this->setApprovalLink($this->_paypalPayment->getApprovalLink());
            return true;
        }
        return false;
    }

    public static function getType(): string
    {
        return Order::TYPE_PAYPAL;
    }

    /**
     * @return array
     */
    public static function identifiableGetParams(): array {
        return ['paymentId', 'token','PayerID'];
    }

    public function findOrder(array $condition = []): ?Order
    {
        if (!isset($condition['paymentId'])) {
            return null;
        }
        return Order::find()
            ->andWhere("JSON_EXTRACT([[payment_details]], '$.paymentId') = :val")
            ->addParams([
                'val' => $condition['paymentId']
            ])
            ->one();
    }

    public function performCheckoutProcedure(Order $order): ?Order
    {
        return $order;
//        var_dump($order->id);exit;
    }

    public function getPaymentDetails(): string
    {
        return json_encode([
            'paymentId' => $this->_paypalPayment->getId()
        ], JSON_THROW_ON_ERROR);
    }
}
