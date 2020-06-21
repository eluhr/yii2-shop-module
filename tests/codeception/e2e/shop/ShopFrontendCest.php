<?php namespace shop;


use Codeception\Example;
use Faker\Factory;

/**
 * Class ShopFrontendCest
 * @group mandatory
 */
class ShopFrontendCest
{

    public function _failed(\E2eTester $I)
    {
        $I->pauseExecution();
    }

    public function checkProductOverview(\E2eTester $I)
    {
        $I->amOnPage('/shop/default/index');
        $I->waitForElementVisible('.items-view');
        $I->see('Technik', '.filters');
        $I->see('Schuhe', '.filters');
        $I->see('Farben', '.filters');
        $I->seeNumberOfElements('.list-item', 3);
        $I->see('iPhone SE');
        $I->see('Nike Air Force 1 \'07');
        $I->see('Sticker');
    }

    public function checkProductFilter(\E2eTester $I)
    {
        $I->amOnPage('/shop/default/index');
        $I->waitForElementVisible('.items-view');
        $I->seeNumberOfElements('.list-item', 3);
        $I->checkOption('input[type="radio"][name="Filter[tag][1][]"][value="1"]');
        $I->waitForElementVisible('.filters a');
        $I->seeNumberOfElements('.list-item', 1);
        $I->see('iPhone SE');
        $I->dontSee('Nike Air Force 1 \'07');
        $I->dontSee('Sticker');
        $I->click('Reset');
        $I->seeNumberOfElements('.list-item', 3);
        $I->checkOption('input[type="radio"][name="Filter[tag][3][]"][value="6"]');
        $I->waitForElementVisible('.filters a');
        $I->seeNumberOfElements('.list-item', 2);
        $I->see('iPhone SE');
        $I->dontSee('Nike Air Force 1 \'07');
        $I->see('Sticker');
        $I->checkOption('input[type="radio"][name="Filter[tag][1][]"][value="1"]');
        $I->waitForElementNotVisible('#a-nike-air-force-1-07-2');
        $I->see('iPhone SE');
        $I->dontSee('Nike Air Force 1 \'07');
        $I->see('Sticker');
        $I->seeNumberOfElements('.list-item', 2);
    }

    public function checkProductDetail(\E2eTester $I)
    {
        $I->amOnPage('/shop/default/index');
        $I->waitForElementVisible('.items-view');
        $I->click('#a-iphone-se-1 .variant-link-list li:first-of-type a');
        $I->waitForElementVisible('.item-detail-view');
        $I->see('iPhone SE', '.product-title');
        $I->see('64 GB Weiß', '.variant-title');
        $I->see('128 GB Schwarz', '.variant-link');
        $I->seeNumberOfElements('.variant-link', 1);
        $I->click('.variant-link');
        $I->waitForText('128 GB Schwarz');
        $I->see('64 GB Weiß', '.variant-link');
        $I->click('Back');
        $I->waitForElementVisible('.items-view');
    }

    /**
     * @example { "url": "/shop/shopping-cart/overview"}
     * @example { "url": "/shop/shopping-cart/checkout"}
     * @example { "url": "/shop/shopping-cart/update-quantity"}
     * @example { "url": "/shop/shopping-cart/check-discount-code"}
     */
    public function checkShoppingCartAccess(\E2eTester $I, Example $example)
    {
        $I->amOnPage($example['url']);
        $I->waitForText('Your shopping cart is currently empty. Please add some products to proceed.');
    }

    public function updateShoppingCart(\E2eTester $I)
    {
        $I->amOnPage('/shop/default/index');
        $I->waitForElementVisible('.items-view');
        $I->click('#a-iphone-se-1 .variant-link-list li:first-of-type a');
        $I->waitForElementVisible('.item-detail-view');
        $I->fillField('#shoppingcartmodify-quantity', 5);
        $I->click('Add to shopping cart');
        $I->waitForText('Added course to shopping cart');

        $I->amOnPage('/shop/shopping-cart/update-quantity');
        $I->see('Method Not Allowed');
        $I->amOnPage('/shop/shopping-cart/check-discount-code');
        $I->see('Method Not Allowed');


        $I->amOnPage('/shop/shopping-cart/overview');
        $I->waitForElementVisible('.shopping-cart-overview-view');
        $I->see('iPhone SE - 64 GB Weiß');
        $I->see('479', 'table tbody tr:first-of-type td:nth-of-type(2)');
        $I->see('5', 'table tbody tr:first-of-type .quantity');
        $I->see('2.395', 'table tbody tr:first-of-type td:last-of-type');
        $I->see('Versandkosten');
        $I->see('2.405', 'table tfoot tr:first-of-type th:last-of-type');

        $I->click('table tbody tr:first-of-type button[type="submit"][data-action="increase"]');
        $I->waitForText('6', 10, 'table tbody tr:first-of-type .quantity');
        $I->see('2.884', 'table tfoot tr:first-of-type th:last-of-type');

        $I->fillField('#shoppingcartdiscount-discount_code', 'sale-40');
        $I->click('Apply Discount Code');
        $I->waitForText('Discount Code code is expired or does not exist');
        $I->fillField('#shoppingcartdiscount-discount_code', 'sale-30');
        $I->click('Apply Discount Code');
        $I->waitForText('-30%');
        $I->see('2.021.80', 'table tfoot tr:first-of-type th:last-of-type');

        $I->click('table tbody tr:nth-of-type(2) button[data-action="remove"]');
        $I->wait(2);
        $I->see('2.884', 'table tfoot tr:first-of-type th:last-of-type');

        $I->click('table tbody tr:first-of-type button[type="submit"][data-action="decrease"]');
        $I->waitForText('5', 10, 'table tbody tr:first-of-type .quantity');
        $I->see('2.405', 'table tfoot tr:first-of-type th:last-of-type');
        $I->click('table tbody tr:first-of-type button[data-action="remove"]');
        $I->waitForText('Your shopping cart is currently empty. Please add some products to proceed.');
    }

    public function checkoutShoppingCart(\E2eTester $I)
    {
        $I->amOnPage('/shop/default/index');
        $I->waitForElementVisible('.items-view');
        $I->click('#a-iphone-se-1 .variant-link-list li:first-of-type a');
        $I->waitForElementVisible('.item-detail-view');
        $I->click('Add to shopping cart');
        $I->waitForText('Added course to shopping cart');


        $I->amOnPage('/shop/shopping-cart/overview');
        $I->waitForElementVisible('.shopping-cart-overview-view');
        $I->click('Continue to checkout');
        $I->waitForElementVisible('.shopping-cart-checkout-view');

        $faker = Factory::create('de_DE');

        $I->fillField('#shoppingcartcheckout-first_name', $faker->firstName);
        $I->fillField('#shoppingcartcheckout-surname', $faker->lastName);
        $I->fillField('#shoppingcartcheckout-email', $faker->email);
        $I->fillField('#shoppingcartcheckout-street_name', $faker->streetName);
        $I->fillField('#shoppingcartcheckout-house_number', $faker->numberBetween(0, 10));
        $I->fillField('#shoppingcartcheckout-postal', $faker->postcode);
        $I->fillField('#shoppingcartcheckout-city', $faker->city);
        $I->checkOption('#shoppingcartcheckout-agb_and_gdpr');
        $I->click('Bestellung kostenpflichtig abschließen');

        $I->waitForElementVisible('.prepayment-view');
        $I->see('479.00');
        $I->see('10.00');
        $I->see('489.00');
        $I->see('__STATUS_RECEIVED__');
    }

}
