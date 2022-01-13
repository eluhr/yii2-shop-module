<?php

namespace eluhr\shop\components;

use eluhr\shop\components\interfaces\PaymentInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use yii\base\Component;
use yii\helpers\Url;
use yii\web\HttpException;

/**
 * --- PROPERTIES ---
 *
 * @property Client $_client
 */
class SaferPayPayment extends Component implements PaymentInterface
{

    private $_body_data = [];

    private $_client;

    protected $_items = [];

    protected $_response_data;

    protected $_orderId;

    public $currency = 'CHF';

    public function init()
    {
        parent::init();

        $this->_client = new Client([
            'base_uri' => getenv('SAFERPAY_BASE_URL'),
            'timeout' => 10
        ]);

        $this->_body_data = [
            'RequestHeader' => [
                'SpecVersion' => '1.10',
                'CustomerId' => getenv('SAFERPAY_CUSTOMER_ID'),
                'RequestId' => uniqid('dd-os-', false),
                'RetryIndicator' => 0
            ],
            'TerminalId' => getenv('SAFERPAY_TERMINAL_ID'),
            'ReturnUrls' => [
                'Success' => Url::to(['/shop/shopping-cart/success-saferpay'], true),
                'Fail' => Url::to(['/shop/shopping-cart/cancelled'], true)
            ]
        ];
    }

    public function setSuccessUrl($orderId)
    {
        $this->_body_data['ReturnUrls']['Success'] = Url::to(['/shop/shopping-cart/success-saferpay','orderId' => $orderId], true);
    }

    public function getOrderId()
    {
        return $this->_orderId;
    }

    public function setOrderId($orderId)
    {
        $this->_orderId = $orderId;
    }


    public function addItem(array $itemData)
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
                    'CurrencyCode' => $this->currency
                ],
                'OrderId' => uniqid('dd-os-', false),
                'Description' => $itemData['name']
            ];
        }
    }

    public function setShippingCost($value)
    {
        $this->addItem([
            'price' => $value,
            'quantity' => 1,
            'name' => 'Versandkosten'
        ]);
    }

    public function execute()
    {
        $total = 0.00;
        foreach ($this->_items as $item) {
            $total += (float)($item['Amount']['Value'] ?? 0);
        }

        $this->_body_data['Payment'] = [
            'Amount' => [
                'Value' => (string)$total,
                'CurrencyCode' => $this->currency
            ],
            'OrderId' => $this->getOrderId() ?: uniqid('dd-os-', false),
            'Description' => 'Bestellung'
        ];

        $request = new Request('POST', 'api/Payment/v1/PaymentPage/Initialize', [
            'Content-Type' => 'application/json; charset=utf-8',
            'Authorization' => 'Basic ' . base64_encode(getenv('SAFERPAY_USERNAME') . ':' . getenv('SAFERPAY_PASSWORD'))
        ], json_encode($this->_body_data));

        try {
            $response = $this->_client->send($request);
        } catch (BadResponseException $e) {
            \Yii::$app->getModule('audit')->exception($e);
            \Yii::debug($e->getResponse()->getBody()->getContents());
            throw new HttpException($e->getCode(), $e->getMessage());
        }

        $this->_response_data = json_decode($response->getBody()->getContents(), true);
        return isset($this->_response_data['RedirectUrl']);
    }

    /**
     * @return mixed
     */
    public function getApprovalLink()
    {
        return $this->_response_data['RedirectUrl'];
    }

}
