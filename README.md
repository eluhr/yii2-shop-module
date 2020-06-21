# Install

```bash
composer require eluhr/yii2-shop-module
```
# Example Config

```php
use kartik\grid\Module as GridViewModule;
use eluhr\shop\Module as ShopModule;
use eluhr\shop\components\PayPalPayment;
use eluhr\shop\components\ShoppingCart;

$requiredEnvs = [
    'PAYPAL_MODE',
    'PAYPAL_CLIENT_ID',
    'PAYPAL_CLIENT_SECRET'
];
foreach ($requiredEnvs as $requiredEnv) {
    if (getenv($requiredEnv) === false) {
        throw new Exception('ENV ' . $requiredEnv . ' not set');
    }
}

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
            'class' => PayPalPayment::class,
            'mode' => getenv('PAYPAL_MODE') ?: 'sandbox',
            'clientId' => getenv('PAYPAL_CLIENT_ID'),
            'clientSecret' => getenv('PAYPAL_CLIENT_SECRET'),
            'returnUrl' => ['/shop/shopping-cart/success'],
            'cancelUrl' => ['/shop/shopping-cart/canceled']
        ],
        'urlManager' => [
            'rules' => [
                'shop' => 'shop/default/index',
                'warenkorb/uebersicht' => 'shop/shopping-cart/overview',
                'warenkorb/checkout' => 'shop/shopping-cart/checkout',
                'warenkorb/meine-bestellung/<orderId>' => 'shop/shopping-cart/prepayment',
                'shop/meine-bestellung/<orderId>' => 'shop/shopping-cart/order',
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

#Potential ideas

 - Wishlist
 - Project as Yii2 extension
 - Translatable(s)!!! (v2)
 - View files from .php to .twig
 - Events for Order and Product (out of stock?)
 
 
# Giiant

```bash
docker-compose run --rm php yii shop:crud --appconfig=/app/vendor/eluhr/yii2-shop-module/src/config/giiant.php
````
