<?php

namespace eluhr\shop\components;

use eluhr\shop\interfaces\PaymentProvider;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\Url;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 *
 * @property array $providers
 * @property string $_provider
 */
class Payment extends Component
{

    /**
     * List of available payment providers
     * These should include the whole config for each provider
     *
     * @var array
     */
    public $providers = [];


    /**
     * Selected payment provider
     *
     * @var string
     */
    protected $_provider;


    /**
     * @var PaymentProvider
     */
    protected static $instance;

    /**
     * Currency code in ISO 4217
     *
     * @var string
     */
    public $currency = 'EUR';

    /**
     * Newly created order id
     *
     * @var string
     */
    protected $_orderId;

    /**
     * @param string $provider
     */
    public function setProvider(string $provider): void
    {
        $this->_provider = $provider;
    }

    /**
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        if (!isset(array_keys($this->providers)[0])) {
            throw new InvalidConfigException('Add at least one payment provider for the payment component');
        }
        if (empty($this->_provider)) {
            // set to first provider by default of nothing is defined
            $this->setProvider(array_keys($this->providers)[0]);
        }
    }

    /**
     * @return \eluhr\shop\interfaces\PaymentProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function paymentProvider(): PaymentProvider
    {
        if (!(self::$instance instanceof PaymentProvider)) {
            // selected provider does not exist
            if (!isset($this->providers[$this->_provider])) {
                throw new InvalidConfigException('Provider does not exit');
            }
            self::$instance = $this->createProvider($this->_provider);
        }
        return self::$instance;
    }


    /**
     * @param array $itemData
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public function addItem(array $itemData): void
    {
        $this->paymentProvider()->addItem($itemData);
    }

    public function setShippingCost($value): void
    {
        $this->paymentProvider()->setShippingCost($value);
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function execute(): bool
    {
        return $this->paymentProvider()->execute();
    }

    /**
     * @param string $orderId
     */
    public function setOrderId(string $orderId): void
    {
        $this->_orderId = $orderId;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->_orderId;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getApprovalLink(): string
    {
        return $this->paymentProvider()->getApprovalLink();
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getSuccessUrl()
    {
        return $this->paymentProvider()->getSuccessUrl();
    }

    /**
     * @param string $type
     * @return PaymentProvider|false
     * @throws \yii\base\InvalidConfigException
     */
    public function createProvider(string $type)
    {
        // Payment provider does not exist
        if (!isset($this->providers[$type])) {
            return false;
        }
        $this->setProvider($type);
        // create object and ensure it is a valid payment provider
        $provider = Instance::ensure(\Yii::createObject($this->providers[$this->_provider]), PaymentProvider::class);
        $provider->setCurrency($this->currency);
        return $provider;
    }

    public function getPaymentDetails(): string
    {
        return $this->paymentProvider()->getPaymentDetails();
    }
}
