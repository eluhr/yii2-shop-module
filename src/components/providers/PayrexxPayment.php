<?php

namespace eluhr\shop\components\providers;

use eluhr\shop\components\traits\ApprovalLink;
use eluhr\shop\interfaces\ExternalPaymentProvider;
use eluhr\shop\models\Order;
use Payrexx\Communicator;
use Payrexx\Models\Request\Gateway;
use Payrexx\Payrexx;
use Payrexx\PayrexxException;
use yii\base\NotSupportedException;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 */
class PayrexxPayment extends BasePaymentProvider implements ExternalPaymentProvider
{

    use ApprovalLink;

    public $apiKey;
    public $instanceName;
    public $communicationHandler = '';
    public $apiBaseDomain = Communicator::API_URL_BASE_DOMAIN;

    protected $_payrexx;
    protected $_successUrl;

    public function init()
    {
        parent::init();
        $this->_payrexx = new Payrexx($this->instanceName, $this->apiKey, $this->communicationHandler, $this->apiBaseDomain);
    }



    public function addItem(array $itemData): void
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

    public function execute(): bool
    {
        $referenceId = \Yii::$app->payment->getOrderId() ?: uniqid('order-', true);
        $gateway = new Gateway();
        $gateway->setReferenceId($referenceId);
        $gateway->setAmount($this->getTotalPrice());
        $gateway->setCurrency($this->_currency);
        $gateway->setSuccessRedirectUrl($this->getSuccessUrl());
        $gateway->setSkipResultPage(true);

        try {
            $response = $this->_payrexx->create($gateway);
            if ($response instanceof \Payrexx\Models\Response\Gateway) {
                $this->setApprovalLink($response->getLink());
                return true;
            }
        } catch (PayrexxException $e) {
            \Yii::error($e->getMessage(), __METHOD__);
        }
        return false;
    }


    static function getType(): string
    {
        return Order::TYPE_PAYREXX;
    }

    public function performCheckoutProcedure(Order $order): ?Order
    {
        $order->status = Order::STATUS_RECEIVED_PAID;
        return $order;
    }

    /**
     * @return array
     */
    public static function identifiableGetParams(): array {
        return ['orderId', 'type'];
    }

    public function findOrder(array $condition = []): ?Order
    {
        throw new NotSupportedException();
    }
}
