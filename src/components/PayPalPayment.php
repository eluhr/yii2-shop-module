<?php


namespace eluhr\shop\components;

use eluhr\shop\components\interfaces\PaymentInterface;
use PayPal\Api\Amount;
use PayPal\Api\Authorization;
use PayPal\Api\Currency;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingCost;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use yii\base\Component;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * @property string $mode
 * @property string $clientId
 * @property string $clientSecret
 * @property string $logDirectory
 * @property string $currency
 * @property string|array $returnUrl
 * @property string|array $cancelUrl
 *
 * @property ApiContext $_apiContext
 * @property Item[] $_items
 * @property float $_total
 *
 */
class PayPalPayment extends Component implements PaymentInterface
{
    public const MODE_SANDBOX = 'sandbox';
    public const MODE_LIVE = 'live';

    public $mode;
    public $clientId;
    public $clientSecret;
    public $logDirectory = '@runtime/paypal-logs';
    public $returnUrl;
    public $cancelUrl;
    public $currency = 'EUR';

    private $_apiContext;
    private $_items = [];
    private $_shippingCost = 0;
    private $_total = 0;


    public function init()
    {
        parent::init();

        if (!defined('PP_CONFIG_PATH')) {
            define('PP_CONFIG_PATH', __DIR__);
        }

        $this->_apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->clientId,
                $this->clientSecret
            )
        );

        $logPath = \Yii::getAlias($this->logDirectory);
        $this->_apiContext->setConfig(
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

        if (!is_file($this->_apiContext->getConfig()['log.FileName']) && !touch($this->_apiContext->getConfig()['log.FileName'])) {
            throw new \ErrorException(\Yii::t('shop', 'Error while creating log file'));
        }
        if (!is_file($this->_apiContext->getConfig()['cache.FileName']) && !touch($this->_apiContext->getConfig()['cache.FileName'])) {
            throw new \ErrorException(\Yii::t('shop', 'Error while creating auth cache file'));
        }
    }

    public function getContext()
    {
        return $this->_apiContext;
    }

    public function addItem(array $itemData)
    {
        $item = new Item();
        $item->setName($itemData['name']);
        $item->setCurrency($this->currency);
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

    public function setShippingCost($shippingCost)
    {
        $this->_shippingCost = $shippingCost;
    }

    /**
     * @return bool|Payment
     */
    public function execute()
    {
        if (!empty($this->_items)) {
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $item = new Item();
            $item->setName(\Yii::t('shop', 'Shipping cost', [], 'de'))
                ->setCurrency($this->currency)
                ->setQuantity(1)
                ->setPrice($this->_shippingCost);

            $this->_total += $this->_shippingCost;
            $this->_items[] = $item;

            $itemList = new ItemList();
            $itemList->setItems($this->_items);

            $amount = new Amount();
            $amount->setCurrency($this->currency)
                ->setTotal((float)$this->_total);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription(\Yii::t('shop', '__PAYPAL_TRANSACTION_DESCRIPTION__'))
                ->setInvoiceNumber(uniqid('', false));

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(Url::to($this->returnUrl, 'http' . (YII_ENV_DEV ? '' : 's')))
                ->setCancelUrl(Url::to($this->cancelUrl, 'http' . (YII_ENV_DEV ? '' : 's')));

            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

            try {
                $payment->create($this->_apiContext);
            } catch (\Exception $e) {
                \Yii::error($e->getMessage(), __METHOD__);
                return false;
            }
            return $payment;
        }
        return false;
    }
}
