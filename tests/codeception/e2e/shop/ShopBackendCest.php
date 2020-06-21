<?php namespace shop;


use Codeception\Example;

/**
 * Class ShopBackendCest
 * @group mandatory
 */
class ShopBackendCest
{

    public function _failed(\E2eTester $I)
    {
        $I->pauseExecution();
    }

    public function checkAccess(\E2eTester $I)
    {
        $data = [
            ["url" => "/shop/crud/discount-code/index", "keyword" => "Discount Codes"],
            ["url" => "/shop/crud/discount-code/create", "keyword" => "Discount Code"],
            ["url" => "/shop/crud/filter/index", "keyword" => "Filters"],
            ["url" => "/shop/crud/filter/create", "keyword" => "Filter"],
            ["url" => "/shop/crud/order-item/index", "keyword" => "Order Items"],
            ["url" => "/shop/crud/order-item/create", "keyword" => "Order Item"],
            ["url" => "/shop/crud/order/index", "keyword" => "Orders"],
            ["url" => "/shop/crud/order/create", "keyword" => "Order"],
            ["url" => "/shop/crud/product/index", "keyword" => "Products"],
            ["url" => "/shop/crud/product/create", "keyword" => "Product"],
            ["url" => "/shop/crud/setting/index", "keyword" => "Settings"],
            ["url" => "/shop/crud/setting/create", "keyword" => "Setting"],
            ["url" => "/shop/crud/tag-x-filter/index", "keyword" => "Tag X Filters"],
            ["url" => "/shop/crud/tag-x-filter/create", "keyword" => "Tag X Filter"],
            ["url" => "/shop/crud/tag-x-product/index", "keyword" => "Tag X Products"],
            ["url" => "/shop/crud/tag-x-product/create", "keyword" => "Tag X Product"],
            ["url" => "/shop/crud/tag/index", "keyword" => "Tags"],
            ["url" => "/shop/crud/tag/create", "keyword" => "Tag"],
            ["url" => "/shop/crud/variant/index", "keyword" => "Variants"],
            ["url" => "/shop/crud/variant/create", "keyword" => "Variant"],
            ["url" => "/shop/dashboard/index", "keyword" => "Dashboard"],
            ["url" => "/shop/dashboard/filters", "keyword" => "New"],
            ["url" => "/shop/dashboard/filter-edit", "keyword" => "New Filter"],
            ["url" => "/shop/dashboard/tags", "keyword" => "New"],
            ["url" => "/shop/dashboard/tag-edit", "keyword" => "New Tag"],
            ["url" => "/shop/dashboard/products", "keyword" => "New"],
            ["url" => "/shop/dashboard/product-edit", "keyword" => "New Product"],
            ["url" => "/shop/dashboard/orders", "keyword" => "STATUS RECEIVED PAID"],
            ["url" => "/shop/dashboard/orders?status=RECEIVED", "keyword" => "STATUS RECEIVED"],
            ["url" => "/shop/dashboard/orders?status=RECEIVED+PAID", "keyword" => "STATUS RECEIVED PAID"],
            ["url" => "/shop/dashboard/orders?status=IN+PROGRESS", "keyword" => "STATUS IN PROGRESS"],
            ["url" => "/shop/dashboard/orders?status=SHIPPED", "keyword" => "STATUS SHIPPED"],
            ["url" => "/shop/dashboard/orders?status=FINISHED", "keyword" => "STATUS FINISHED"],
            ["url" => "/shop/dashboard/discount-codes", "keyword" => "New"],
            ["url" => "/shop/dashboard/discount-code-edit", "keyword" => "New Discount code"],
            ["url" => "/shop/dashboard/products-configurator", "keyword" => "Back"],
            ["url" => "/shop/dashboard/settings", "keyword" => "General Settings"]
        ];
        foreach ($data as $item) {
            $I->amOnPage($item['url']);
            $I->seeInCurrentUrl('user/login');
        }

        $I->login('admin', 'admin1');
        foreach ($data as $item) {
            $I->amOnPage($item['url']);
            $I->waitForText($item['keyword']);
        }
    }

    public function checkAjaxEndpoints(\E2eTester $I)
    {
        $links = [
            '/shop/data/add-tag-to-filter',
            '/shop/data/add-tag-to-product',
            '/shop/data/sort-products',
            '/shop/data/sort-filters',
            '/shop/data/sort-variants',
            '/shop/data/toggle-product-status',
            '/shop/data/toggle-filter-status',
            '/shop/data/sort-filter-tags',
            '/shop/data/remove-tag',
        ];

        foreach ($links as $link) {
            $I->amOnPage($link);
            $I->seeInCurrentUrl('user/login');
        }
    }


    public function checkDashboardLinksExist(\E2eTester $I)
    {
        $data = [
            ["url" => "shop/dashboard/filters", "keyword" => "Filters"],
            ["url" => "shop/dashboard/tags", "keyword" => "Tags"],
            ["url" => "shop/dashboard/products", "keyword" => "Products"],
            ["url" => "shop/dashboard/orders", "keyword" => "Orders"],
            ["url" => "shop/dashboard/discount-codes", "keyword" => "Discount codes"],
            ["url" => "shop/dashboard/products-configurator", "keyword" => "Products Configurator"],
            ["url" => "shop/dashboard/settings", "keyword" => "Settings"]
        ];

        $I->login('admin', 'admin1');
        $I->amOnPage('/shop/dashboard/index');
        foreach ($data as $item) {
            $I->see($item['keyword'], "a[href$='{$item['url']}']");
        }
    }

    public function checkFilter(\E2eTester $I)
    {
        $I->login('admin', 'admin1');
        $I->amOnPage('/shop/dashboard/filters');
        $I->waitForText('New');
        $I->click('New');
        $I->waitForText('New Filter');
        $I->click('Save');
        $I->waitForText('Name cannot be blank.');
        $I->waitForText('Rank cannot be blank.');

        $newName = uniqid('FILTER-', false);
        $I->fillField('#filter-name', $newName);
        $I->checkOption('#filter-is_online');
        $I->fillField('#filter-rank', 0);
        $I->click('Save');
        $I->waitForText($newName);
        $I->click('Back');
        $I->waitForElementVisible('.grid-view');
        $I->see($newName);
    }

    public function checkTag(\E2eTester $I)
    {
        $I->login('admin', 'admin1');
        $I->amOnPage('/shop/dashboard/tags');
        $I->waitForText('New');
        $I->click('New');
        $I->waitForText('New Tag');
        $I->click('Save');
        $I->waitForText('Name cannot be blank.');

        $newName = uniqid('TAG-', false);
        $I->fillField('#tag-name', $newName);
        $I->click('Save');
        $I->waitForText($newName);
        $I->click('Back');
        $I->waitForElementVisible('.grid-view');
        $I->see($newName);
    }

    public function checkProductAndVariant(\E2eTester $I)
    {
        $I->login('admin', 'admin1');
        $I->amOnPage('/shop/dashboard/products');
        $I->waitForText('New');
        $I->click('New');
        $I->waitForText('New Product');
        $I->click('Save');
        $I->waitForText('Title cannot be blank.');
        $I->waitForText('Rank cannot be blank.');
        $I->waitForText('Shipping Price cannot be blank.');

        $newProductName = uniqid('PRODUCT-', false);
        $I->fillField('#product-title', $newProductName);
        $I->fillField('#product-rank', 0);
        $I->fillField('#product-shipping_price-disp', 10);
        $I->click('Save');
        $I->waitForText($newProductName);
        $I->see('New Variant');
        $I->click('New Variant');
        $I->see('New Variant', 'h2');
        $I->click('Save');
        $I->waitForText('Title cannot be blank.');
        $I->waitForText('Price cannot be blank.');
        $I->waitForText('Rank cannot be blank.');
        $I->waitForText('Hex Color cannot be blank.');
        $I->waitForText('Stock cannot be blank.');

        $newVariantName = 'VARIANT-' . $newProductName;
        $I->fillField('#variant-title', $newVariantName);
        $I->fillField('#variant-price-disp', 20);
        $I->fillField('#variant-rank', 0);
        $I->fillField('#variant-hex_color', '#000000');
        $I->fillField('#variant-stock', '11');
        $I->click('Save');
    }


    public function checkDiscountCode(\E2eTester $I)
    {
        $I->login('admin', 'admin1');
        $I->amOnPage('/shop/dashboard/discount-codes');
        $I->waitForText('New');
        $I->click('New');
        $I->waitForText('New Discount code');
        $I->click('Save');
        $I->waitForText('Code cannot be blank.');
        $I->waitForText('Percent cannot be blank.');
        $I->waitForText('Expiration Date cannot be blank.');

        $newName = uniqid('CODE-', false);
        $I->fillField('#discountcode-code', $newName);
        $I->fillField('#discountcode-percent', random_int(1, 65));
        $I->fillField('#discountcode-expiration_date', date('d.m.Y'));
        $I->click('Save');
        $I->waitForText($newName);
        $I->click('Back');
        $I->waitForElementVisible('.grid-view');
        $I->see($newName);
    }

}
