# Install

```bash
composer require eluhr/yii2-shop-module
```
# Example Config

```php
use kartik\grid\Module as GridViewModule;
use eluhr\shop\Module as ShopModule;
use eluhr\shop\components\Payment;
use eluhr\shop\components\providers\DirectDebitPayment;
use eluhr\shop\components\providers\PayPalPayment;
use eluhr\shop\components\providers\PayrexxPayment;
use eluhr\shop\components\providers\SaferPayPayment;
use eluhr\shop\models\Order;
use eluhr\shop\components\ShoppingCart;

return [
'aliases' => [
        'eluhr/shop' => '@vendor/eluhr/yii2-shop-module/src'
],
'modules' => [
        'shop' => [
            'class' => ShopModule::class,
        ],
         'gridview' => [
                    'class' => GridViewModule::class
         ]
],
'components' => [
        'shoppingCart' => [
            'class' => ShoppingCart::class
        ],
        'payment' => [
            'class' => Payment::class,
            'currency' => 'EUR',
            'providers' => [
                Order::TYPE_PREPAYMENT => [
                    'class' => DirectDebitPayment::class
                ],
                Order::TYPE_PAYREXX => [
                    'class' => PayrexxPayment::class,
                    'apiKey' => getenv('PAYREXX_API_KEY'),
                    'instanceName' => getenv('PAYREXX_INSTANCE_NAME'),
                    'apiBaseDomain' => getenv('PAYREXX_API_BASE_DOMAIN')
                ],
                Order::TYPE_PAYPAL => [
                    'class' => PayPalPayment::class,
                    'mode' => getenv('PAYPAL_MODE'),
                    'clientId' => getenv('PAYPAL_CLIENT_ID'),
                    'clientSecret' => getenv('PAYPAL_CLIENT_SECRET')
                ],
                Order::TYPE_SAFERPAY => [
                    'class' => SaferPayPayment::class,
                    'baseUrl' => getenv('SAFERPAY_BASE_URL'),
                    'customerId' => getenv('SAFERPAY_CUSTOMER_ID'),
                    'terminalId' => getenv('SAFERPAY_TERMINAL_ID'),
                    'username' => getenv('SAFERPAY_USERNAME'),
                    'password' => getenv('SAFERPAY_PASSWORD')
                ]
            ]
        ],
        'urlManager' => [
            'rules' => [
                'shop' => 'shop/default/index',
                'warenkorb/uebersicht' => 'shop/shopping-cart/overview',
                'warenkorb/checkout' => 'shop/shopping-cart/checkout',
                'warenkorb/meine-bestellung/<orderId>' => 'shop/shopping-cart/prepayment',
                'shop/meine-bestellungen' => 'shop/orders/all',
                'shop/meine-bestellungen/<orderId>' => 'shop/orders/detail',
                'shop/<productTitle>-<productId:\d+>/<variantTitle>-<variantId:\d+>' => 'shop/product/detail',
                'shop/<productTitle>-<productId:\d+>' => 'shop/product/detail'
            ],
            'ignoreLanguageUrlPatterns' => [
                '#^shop/shopping-cart/check-discount-code#' => '#^shop/shopping-cart/check-discount-code#',
                '#^shop/dashboard/update-shipping-link#' => '#^shop/dashboard/update-shipping-link#',
                '#^shop/data/add-tag-to-filter#' => '#^shop/data/add-tag-to-filter#',
                '#^shop/data/add-tag-to-product#' => '#^shop/data/add-tag-to-product#',
                '#^shop/data/sort-filter-tags#' => '#^shop/data/sort-filter-tags#',
                '#^shop/data/sort-products#' => '#^shop/data/sort-products#',
                '#^shop/data/sort-filters#' => '#^shop/data/sort-filters#',
                '#^shop/data/sort-variants#' => '#^shop/data/sort-variants#',
                '#^shop/data/update-email-template#' => '#^shop/data/update-email-template#',
                '#^shop/data/update-email-template-context-menu#' => '#^shop/data/update-email-template-context-menu#',
                '#^shop/data/load-template#' => '#^shop/data/load-template#',
            ]
        ]
    ],
'controllerMap' => [
        'migrate' => [
            'migrationPath' => [
                '@vendor/eluhr/yii2-shop-module/src/migrations'
            ]
        ]
    ]
];
```
# frontend access (optional) 

if you want to add access checks to the frontend controllers, you can add the desired rules via module config

Example:
```php
    'modules' => [
        'shop'     => [
            'class' => ShopModule::class,
            # .... other shop configs
            'frontendAccessRules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ],
    ],
```


# Future plans

 - Add orders to user by id and postal

 
# Giiant

```bash
docker-compose run --rm php yii shop:crud --appconfig=/app/vendor/eluhr/yii2-shop-module/src/config/giiant.php
````
