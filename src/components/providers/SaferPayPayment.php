<?php

namespace eluhr\shop\components\providers;

use eluhr\shop\components\traits\ApprovalLink;
use eluhr\shop\interfaces\ExternalPaymentProvider;
use eluhr\shop\models\Order;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use yii\base\NotSupportedException;
use yii\helpers\Url;
use yii\web\HttpException;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 */
class SaferPayPayment extends BasePaymentProvider implements ExternalPaymentProvider
{

    use ApprovalLink;

    public $baseUrl;
    public $customerId;
    public $terminalId;
    public $returnUrl = ['/shop/shopping-cart/success'];
    public $cancelUrl = ['/shop/shopping-cart/cancelled'];
    public $username;
    public $password;
    /**
     * @var \GuzzleHttp\Client
     */
    protected $_client;

    protected $_body_data;


    public function init()
    {
        parent::init();

        $this->_client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 10
        ]);

        $this->_body_data = [
            'RequestHeader' => [
                'SpecVersion' => '1.10',
                'CustomerId' => $this->customerId,
                'RequestId' => uniqid('dd-os-', false),
                'RetryIndicator' => 0
            ],
            'TerminalId' => $this->terminalId,
            'ReturnUrls' => [
                'Success' => Url::to($this->returnUrl, true),
                'Fail' => Url::to($this->cancelUrl, true)
            ]
        ];
    }

    /**
     * @return array
     */
    public static function identifiableGetParams(): array
    {
        return ['orderId', 'type'];
    }

    public function findOrder(array $condition = []): ?Order
    {
        throw new NotSupportedException();
    }

    public function addItem(array $itemData): void
    {
        $add = true;
        if (isset($itemData['isDiscount']) && $itemData['isDiscount'] === true) {
            $add = false;
        }

        if ($add) {
            $price = (float)($itemData['price'] ?? 0) * (float)($itemData['quantity'] ?? 0) * 100;
            $this->_items[] = [
                'Amount' => [
                    'Value' => $price,
                    'CurrencyCode' => $this->_currency
                ],
                'OrderId' => uniqid('dd-os-', false),
                'Description' => $itemData['name']
            ];
        }
    }

    public function execute(): bool
    {
        $total = 0.00;
        foreach ($this->_items as $item) {
            $total += (float)($item['Amount']['Value'] ?? 0);
        }

        $this->_body_data['Payment'] = [
            'Amount' => [
                'Value' => (string)$total,
                'CurrencyCode' => $this->_currency
            ],
            'OrderId' => $this->getOrderId() ?: uniqid('dd-os-', false),
            'Description' => 'Bestellung'
        ];

        $request = new Request('POST', 'api/Payment/v1/PaymentPage/Initialize', [
            'Content-Type' => 'application/json; charset=utf-8',
            'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)
        ], json_encode($this->_body_data));

        try {
            $response = $this->_client->send($request);
        } catch (BadResponseException $e) {
            \Yii::$app->getModule('audit')->exception($e);
            \Yii::debug($e->getResponse()->getBody()->getContents());
            throw new HttpException($e->getCode(), $e->getMessage());
        }

        $this->_response_data = json_decode($response->getBody()->getContents(), true);
        if (isset($this->_response_data['RedirectUrl'])) {
            $this->setApprovalLink($this->_response_data['RedirectUrl']);
            return true;
        }
        return false;
    }

    public static function getType(): string
    {
        return Order::TYPE_SAFERPAY;
    }

    public function performCheckoutProcedure(Order $order): ?Order
    {
        $order->status = Order::STATUS_RECEIVED_PAID;
        return $order;
    }
}
