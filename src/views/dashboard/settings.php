<?php

use eluhr\shop\models\ShopSettings;
use eluhr\shop\models\Vat;
use rmrevin\yii\fontawesome\FA;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var ActiveDataProvider $dataProvider
 * @var ShopSettings $setting
 * @var View $this
 */

$this->registerJs(
    <<<JS
$("[data-input='checkbox']").on("click", function(e) {
    if (confirm( $(this).data('confirm-text'))) {
          $(".overlay[data-overlay='" + $(this).data('group') + "']").removeClass('hidden');
          this.form.submit();  
    } else {
     e.preventDefault();   
    }
});
$("[data-input='textfield']").on("blur", function() {
    if (confirm( $(this).data('confirm-text'))) {
          $(".overlay[data-overlay='" + $(this).data('group') + "']").removeClass('hidden');
          this.form.submit();  
    }
});
$("[data-input='dropdown']").on("change", function() {
    if (confirm( $(this).data('confirm-text'))) {
          $(".overlay[data-overlay='" + $(this).data('group') + "']").removeClass('hidden');
          this.form.submit();  
    }
});
$("form").on("afterValidate", function() {
    if ($('.form-group.has-error').length > 0) {
        $('.box .overlay').addClass('hidden');
    }
});
$(".toggle-img").on("click", function(event) {
    event.preventDefault();
    $($(this).data('target')).toggleClass('hidden');
    $(this).find('i').toggleClass('fa-eye-slash');
});
JS
);

$allowedProviders = ShopSettings::allowedPaymentProviders();

?>

<div class="form-group">
    <div class="btn-toolbar">
        <?= Html::a(Yii::t('shop', '{icon} Back', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['index'],
            ['class' => 'btn btn-default']) ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'General Settings') ?></h3>
            </div>
            <div class="box-body">
                <?php
                $form = ActiveForm::begin();
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_SHOW_FILTERS,
                    ['template' => '{input} {label} {error}'])->checkbox([
                    'data-input' => 'checkbox',
                    'data-group' => 'general',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will hide/show the product filter in frontend'),
                    'class' => 'switch-input'
                ], false);
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_SHOW_SEARCH,
                    ['template' => '{input} {label} {error}'])->checkbox([
                    'data-input' => 'checkbox',
                    'data-group' => 'general',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will hide/show the product search input in frontend'),
                    'class' => 'switch-input'
                ], false);
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_INVOICE_DOWNLOAD,
                    ['template' => '{input} {label} {error}'])->checkbox([
                    'data-input' => 'checkbox',
                    'data-group' => 'general',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will enable/disable invoice download in order summary'),
                    'class' => 'switch-input'
                ], false);
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_SHIPPING_LINK,
                    ['template' => '{input} {label} {error}'])->checkbox([
                    'data-input' => 'checkbox',
                    'data-group' => 'general',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will enable/disable shipping link in order summary'),
                    'class' => 'switch-input'
                ], false);
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_SHORT_ORDER_ID,
                    ['template' => '{input} {label} {error}'])->checkbox([
                    'data-input' => 'checkbox',
                    'data-group' => 'general',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will enable/disable short order ids'),
                    'class' => 'switch-input'
                ], false);
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_SHOW_OUT_OF_STOCK_VARIANTS,
                    ['template' => '{input} {label} {error}'])->checkbox([
                    'data-input' => 'checkbox',
                    'data-group' => 'general',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will hide/show out of stock variants'),
                    'class' => 'switch-input'
                ], false);
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_SHOP_SELLS_ADULT_PRODUCTS,
                    ['template' => '{input} {label} {error}'])->checkbox([
                    'data-input' => 'checkbox',
                    'data-group' => 'general',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will hide/show age verification in checkout'),
                    'class' => 'switch-input'
                ], false);
                ?>
                <div class="row">
                    <div class="col-xs-12 col-md-2"><?php echo $form->field($setting,
                            ShopSettings::SHOP_GENERAL_MIN_SHOPPING_CART_VALUE)->input('number', [
                            'data-input' => 'textfield',
                            'data-group' => 'general',
                            'data-confirm-text' => Yii::t('shop',
                                'Are you sure you want to change this setting? This will change the min shopping cart value for all users.'),
                            'step' => 0.01,
                            'min' => 0
                        ]); ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-2"><?php echo $form->field($setting,
                            ShopSettings::SHOP_GENERAL_SHIPPING_COST)->input('number', [
                            'data-input' => 'textfield',
                            'data-group' => 'general',
                            'data-confirm-text' => Yii::t('shop',
                                'Are you sure you want to change this setting? This will change the shipping cost value.'),
                            'step' => 0.01
                        ]); ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-2"><?php echo $form->field($setting,
                            ShopSettings::SHOP_GENERAL_SHIPPING_COST_FREE_LIMIT)->input('number', [
                            'data-input' => 'textfield',
                            'data-group' => 'general',
                            'data-confirm-text' => Yii::t('shop',
                                'Are you sure you want to change this setting? This will change the shipping cost free limit value.'),
                            'step' => 0.01
                        ]); ?></div>
                </div>
                <?php
                ActiveForm::end();
                ?>
            </div>
            <div class="overlay hidden"
                 data-overlay="general"><?= FA::icon(FA::_SPINNER, ['class' => 'fa-spin']) ?></div>
        </div>

        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Product Settings') ?></h3>
            </div>
            <div class="box-body">
                <?php
                $form = ActiveForm::begin();
                ?>
                <div class="row">
                    <div class="col-xs-12 col-md-2"><?php echo $form->field($setting,
                            ShopSettings::SHOP_PRODUCT_FEW_AVAILABLE_WARNING)->input('number', [
                            'data-input' => 'textfield',
                            'data-group' => 'product',
                            'data-confirm-text' => Yii::t('shop',
                                'Are you sure you want to change this setting? This will change warning an logged in user will get if stock is low.')
                        ]); ?></div>
                </div>
                <?php
                echo $form->field($setting, ShopSettings::SHOP_PRODUCT_SHOW_SHIPPING_COSTS,
                    ['template' => '{input} {label} {error}'])->checkbox([
                    'data-input' => 'checkbox',
                    'data-group' => 'product',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will hide/show the shipping costs for all users'),
                    'class' => 'switch-input'
                ], false);
                ?>
                <div class="row">
                    <div class="col-xs-12"><?php echo $form->field($setting, ShopSettings::SHOP_PRODUCT_ENABLE_SHIPPING_DURATION,
                            ['template' => '{input} {label} {error}'])->checkbox([
                            'data-input' => 'checkbox',
                            'data-group' => 'product',
                            'data-confirm-text' => Yii::t('shop',
                                'Are you sure you want to change this setting?'),
                            'class' => 'switch-input'
                        ], false); ?></div>
                    <div class="col-xs-12 col-md-2"><?php echo $form->field($setting,
                            ShopSettings::SHOP_PRODUCT_MIN_DAYS_SHIPPING_DURATION)->input('number', [
                            'data-input' => 'textfield',
                            'data-group' => 'product',
                            'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting?')
                        ]); ?></div>
                    <div class="col-xs-12 col-md-2"><?php echo $form->field($setting,
                            ShopSettings::SHOP_PRODUCT_MAX_DAYS_SHIPPING_DURATION)->input('number', [
                            'data-input' => 'textfield',
                            'data-group' => 'product',
                            'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting?')
                        ]); ?></div>
                </div>
                <?php
                echo $form->field($setting, ShopSettings::SHOP_PRODUCT_VARIANT_TEXT_TEMPLATE)->textarea([
                    'data-input' => 'textfield',
                    'data-group' => 'product',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will change the product variant text template.'),
                    'rows' => 6,
                    'placeholder' => Yii::t('shop', 'You can write your custom HTML template here')
                ]);
                echo $form->field($setting, ShopSettings::SHOP_PRODUCT_SHOW_VAT,
                    ['template' => '{input} {label} {error}'])->checkbox([
                    'data-input' => 'checkbox',
                    'data-group' => 'product',
                    'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting?'),
                    'class' => 'switch-input'
                ], false);
                ?>
                <div class="row">
                    <div class="col-xs-12 col-md-2"><?php echo $form->field($setting,
                            ShopSettings::SHOP_PRODUCT_DEFAULT_VAT_ID)->dropDownList(
                            ArrayHelper::map(Vat::find()->all(), 'id', 'value'),
                            [
                                'prompt' => Yii::t('shop', 'Please select a vat value'),
                                'data-input' => 'dropdown',
                                'data-group' => 'product',
                                'step' => 0.01,
                                'min' => 0,
                                'max' => 100,
                                'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting?')
                            ]
                        ); ?></div>
                </div>
                <?php
                echo $form->field($setting, ShopSettings::SHOP_PRODUCT_ALLOW_CONFIGURABLE_VARIANT, ['template' => '{input} {label} {error}'])->checkbox(['data-input' => 'checkbox', 'data-group' => 'product', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting?'), 'class' => 'switch-input'], false);
                ActiveForm::end();
                ?>
            </div>
            <div class="overlay hidden"
                 data-overlay="product"><?= FA::icon(FA::_SPINNER, ['class' => 'fa-spin']) ?></div>
        </div>

        <div class="box box-solid <?php echo empty($allowedProviders) ? 'box-danger' : 'box-default'?>">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Checkout Settings') ?></h3>
            </div>
            <div class="box-body">
                <?php
                $form = ActiveForm::begin();
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_ENABLE_DISCOUNT_CODES,
                    ['template' => '{input} {label} {error}'])->checkbox([
                    'data-input' => 'checkbox',
                    'data-group' => 'checkout',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will hide/show the discount code in frontend'),
                    'class' => 'switch-input'
                ], false);
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_ALLOW_CUSTOMER_DETAILS,
                    ['template' => '{input} {label} {error}'])->checkbox([
                    'data-input' => 'checkbox',
                    'data-group' => 'checkout',
                    'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting?'),
                    'class' => 'switch-input'
                ], false);
                $setting->{ShopSettings::SHOP_CHECKOUT_PAYMENT_PROVIDERS} = array_keys($allowedProviders);
                echo $form->field($setting,
                    ShopSettings::SHOP_CHECKOUT_PAYMENT_PROVIDERS)->checkboxList(ShopSettings::allPaymentProviders(), [
                    'itemOptions' => [
                        'data-input' => 'checkbox',
                        'data-group' => 'checkout',
                        'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting?'),
                        'class' => 'switch-input'
                    ]
                ]);
                if (empty($allowedProviders)) {
                    echo Html::tag('b', Yii::t('shop','No providers means no checkout is possible. Please choose at least one!'),['class' => 'text-danger']);
                }
                ActiveForm::end();
                ?>
            </div>
            <div class="overlay hidden"
                 data-overlay="checkout"><?= FA::icon(FA::_SPINNER, ['class' => 'fa-spin']) ?></div>
        </div>

        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Invoice Settings') ?></h3>
            </div>
            <div class="box-body">
                <?php
                $form = ActiveForm::begin();
                $invoiceLogoInputTemplate = "{label}\n{input}\n{hint}\n{error}";
                if (!empty($setting->shopInvoiceLogo)) {
                    echo Html::img($setting->shopInvoiceLogo, [
                        'alt' => Yii::t('shop', 'Invoice logo not reachable'),
                        'style' => [
                            'max-width' => '150px'
                        ],
                        'class' => 'hidden',
                        'id' => 'img-' . ShopSettings::SHOP_INVOICE_LOGO
                    ]);
                    $invoiceLogoInputTemplate = '{label}<div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-primary toggle-img" data-target="#img-' . ShopSettings::SHOP_INVOICE_LOGO . '" type="button"><i class="fa fa-eye"></i></button></span></div>{hint}{error}';
                }
                echo $form->field($setting, ShopSettings::SHOP_INVOICE_LOGO, ['template' => $invoiceLogoInputTemplate])->textInput([
                    'data-input' => 'textfield',
                    'data-group' => 'invoice',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will the invoice header logo image.')
                ]);
                ActiveForm::end();
                ?>
            </div>
            <div class="overlay hidden"
                 data-overlay="invoice"><?= FA::icon(FA::_SPINNER, ['class' => 'fa-spin']) ?></div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Mail Settings') ?></h3>
            </div>
            <div class="box-body">
                <h4><?= Yii::t('shop', 'General') ?></h4>
                <?php
                $form = ActiveForm::begin();
                $mailLogoInputTemplate = "{label}\n{input}\n{hint}\n{error}";
                if (!empty($setting->shopMailLogo)) {
                    echo Html::img($setting->shopMailLogo, [
                        'alt' => Yii::t('shop', 'Mail logo not reachable'),
                        'style' => [
                            'max-width' => '150px'
                        ],
                        'class' => 'hidden',
                        'id' => 'img-' . ShopSettings::SHOP_MAIL_LOGO
                    ]);
                    $mailLogoInputTemplate = '{label}<div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-primary toggle-img" data-target="#img-' . ShopSettings::SHOP_MAIL_LOGO . '" type="button"><i class="fa fa-eye"></i></button></span></div>{hint}{error}';
                }
                echo $form->field($setting, ShopSettings::SHOP_MAIL_LOGO,['template' => $mailLogoInputTemplate])->textInput([
                    'data-input' => 'textfield',
                    'data-group' => 'mail',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will the mail header logo image.')
                ]);
                ActiveForm::end();
                ?>
                <?php
                $form = ActiveForm::begin();
                echo $form->field($setting, ShopSettings::SHOP_MAIL_SHOW_BANK_DETAILS,
                    ['template' => '{input} {label} {error}'])->checkbox([
                    'data-input' => 'checkbox',
                    'data-group' => 'mail',
                    'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting?'),
                    'class' => 'switch-input'
                ], false);
                ActiveForm::end();
                ?>
                <h4><?= Yii::t('shop', 'Confirm Mail') ?></h4>
                <?php
                $form = ActiveForm::begin();
                echo $form->field($setting, ShopSettings::SHOP_MAIL_CONFIRM_BCC)->textInput([
                    'data-input' => 'textfield',
                    'data-group' => 'mail',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will change bbc email(s) for the confirm mail.')
                ]);
                echo $form->field($setting, ShopSettings::SHOP_MAIL_CONFIRM_REPLY_TO)->input('email', [
                    'data-input' => 'textfield',
                    'data-group' => 'mail',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will change reply email for the confirm mail.')
                ]);
                echo $form->field($setting, ShopSettings::SHOP_MAIL_CONFIRM_SUBJECT)->textInput([
                    'data-input' => 'textfield',
                    'data-group' => 'mail',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will change the subject for the confirm mail.')
                ]);
                ActiveForm::end();
                ?>
                <p></p>
                <h4><?= Yii::t('shop', 'Info Mail') ?></h4>
                <?php
                $form = ActiveForm::begin();
                echo $form->field($setting, ShopSettings::SHOP_MAIL_INFO_REPLY_TO)->input('email', [
                    'data-input' => 'textfield',
                    'data-group' => 'mail',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will change reply email for the info mail.')
                ]);
                echo $form->field($setting, ShopSettings::SHOP_MAIL_INFO_SUBJECT)->textInput([
                    'data-input' => 'textfield',
                    'data-group' => 'mail',
                    'data-confirm-text' => Yii::t('shop',
                        'Are you sure you want to change this setting? This will change the subject for the info mail.')
                ]);
                ActiveForm::end();
                ?>
            </div>
            <div class="overlay hidden"
                 data-overlay="mail"><?= FA::icon(FA::_SPINNER, ['class' => 'fa-spin']) ?></div>
        </div>
    </div>
</div>

