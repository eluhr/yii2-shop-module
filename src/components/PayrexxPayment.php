<?php

namespace eluhr\shop\components;

use eluhr\shop\components\interfaces\PaymentInterface;
use Payrexx\Models\Request\Gateway;
use Payrexx\Payrexx;
use Payrexx\PayrexxException;
use yii\base\Component;
use yii\helpers\Url;

/**
 * --- PROPERTIES ---
 *
 * @property \Payrexx\Payrexx|\Payrexx\CommunicationAdapter\CurlCommunication $payrexx
 * @property string $currency
 * @property string $baseUri
 * @property string $apiKey
 * @property string $instanceName
 *
 * @author Elias Luhr
 */
class PayrexxPayment extends Component implements PaymentInterface
{

    protected $payrexx;
    protected $_approvalLink;
    protected $_orderId;
    protected $_successUrl;
    protected $_shippingCost = 0;

    protected $_items = [];

    public $currency = 'CHF';

    public $apiKey;
    public $instanceName;

    public function init()
    {
        parent::init();

        $this->payrexx = new Payrexx($this->instanceName, $this->apiKey);
    }


    public function addItem(array $itemData)
    {
        $add = true;

        if (isset($itemData['isDiscount']) && $itemData['isDiscount'] === true) {
            $add = false;
        }

        if ($add) {
            $this->_items[] = [
                'name' => $itemData['name'],
                'price' => (float)($itemData['price'] ?? 0) * (float)($itemData['quantity'] ?? 0) * 100
            ];
        }
    }

    public function getTotalPrice()
    {
        $itemsSum = 0;
        foreach ($this->_items as $item) {
            $itemsSum += $item['price'] ?? 0;
        }
        return $itemsSum + $this->_shippingCost;
    }

    public function getSuccessUrl()
    {
        return $this->_successUrl;
    }

    public function setSuccessUrl($orderId)
    {
        $this->_successUrl = Url::to(['/shop/shopping-cart/success-payrexx', 'orderId' => $orderId], true);
    }

    public function getOrderId()
    {
        return $this->_orderId;
    }

    public function setOrderId($orderId)
    {
        $this->_orderId = $orderId;
    }

    /**
     * Get Approval Link
     *
     * @return null|string
     */
    public function getApprovalLink()
    {
        return $this->_approvalLink;
    }

    /**
     * Get Approval Link
     *
     * @return null|string
     */
    public function setApprovalLink($approvalLink)
    {
        $this->_approvalLink = $approvalLink;
    }

    public function execute()
    {
        $referenceId = $this->getOrderId() ?: uniqid('order-', true);
        $gateway = new Gateway();
        $gateway->setReferenceId($referenceId);
        $gateway->setAmount($this->getTotalPrice());
        $gateway->setCurrency($this->currency);
        $gateway->setSuccessRedirectUrl($this->getSuccessUrl());
        $gateway->setSkipResultPage(true);

        try {
            $response = $this->payrexx->create($gateway);
            if ($response instanceof \Payrexx\Models\Response\Gateway) {
                $this->setApprovalLink($response->getLink());
            }
            return $response;
        } catch (PayrexxException $e) {
            \Yii::error($e->getMessage(), __METHOD__);
        }
        return true;
    }

    public function setShippingCost($value)
    {
        $this->_shippingCost = $value * 100;
    }

}
