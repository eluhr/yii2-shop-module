<?php

namespace eluhr\shop\components;

use eluhr\shop\components\interfaces\PaymentInterface;
use Payrexx\Models\Request\Invoice;
use Payrexx\Payrexx;
use Payrexx\PayrexxException;
use yii\base\Component;
use yii\helpers\Url;
use yii\helpers\VarDumper;

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
            $itemsSum+= $item['price'] ?? 0;
        }
        return $itemsSum + $this->_shippingCost;
    }

    public function getSuccessUrl()
    {
        return $this->_successUrl;
    }

    public function setSuccessUrl($orderId)
    {
        $this->_successUrl = Url::to(['/shop/shopping-cart/success-payrexx','orderId' => $orderId], true);
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
        $invoice = new Invoice();
        $referenceId = $this->getOrderId() ?: uniqid('order-', true);
        $invoice->setReferenceId($referenceId);
        $invoice->setTitle(\Yii::t('shop','Online-Shop Payment'));
        $invoice->setDescription(\Yii::t('shop','Thank you for using Payrexx to pay for your order'));
        $invoice->setPurpose(\Yii::t('shop','Online-Shop Payment {orderId}', ['orderId' => $referenceId]));
        $invoice->setAmount($this->getTotalPrice());
        $invoice->setCurrency($this->currency);
        $invoice->setSuccessRedirectUrl($this->getSuccessUrl());

        try {
            $response = $this->payrexx->create($invoice);
            if ($response instanceof \Payrexx\Models\Response\Invoice) {
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
