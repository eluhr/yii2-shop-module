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
 */
class SaferPayPayment extends Component implements PaymentInterface
{

    private $_body_data = [];

    private $_client;

    protected $_items = [];

    protected $_response_data;

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
                'RequestId' => uniqid('os-r-', false),
                'RetryIndicator' => 0
            ],
            'TerminalId' => getenv('SAFERPAY_TERMINAL_ID'),
            'ReturnUrls' => [
                'Success' => Url::to(['/shop/shopping-cart/success-saferpay'], true),
                'Fail' => Url::to(['/shop/shopping-cart/cancelled'], true)
            ]
        ];
    }


    public function addItem(array $itemData)
    {
        $this->_items[] = [
            'Amount' => [
                'Value' => (float)($itemData['price'] ?? 0) * (float)($itemData[''] ?? 0),
                'CurrencyCode' => $this->currency
            ],
            'OrderId' => uniqid('dd-os-', false),
            'Description' => $itemData['name']
        ];

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
        $request = new Request('POST', 'api/Payment/v1/PaymentPage/Initialize', [
            'Content-Type' => 'application/json; charset=utf-8',
            'Authorization' => 'Basic ' . base64_encode(getenv('SAFERPAY_USERNAME') . ':' . getenv('SAFERPAY_PASSWORD'))
        ], json_encode($this->_body_data));

        try {
            $response = $this->_client->send($request);
        } catch (BadResponseException $e) {
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
