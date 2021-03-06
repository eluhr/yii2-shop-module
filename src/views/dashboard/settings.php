<?php

use eluhr\shop\models\ShopSettings;
use rmrevin\yii\fontawesome\FA;
use yii\data\ActiveDataProvider;
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
$("form").on("afterValidate", function() {
    if ($('.form-group.has-error').length > 0) {
        $('.box .overlay').addClass('hidden');
    }
});
JS
);
?>

<div class="form-group">
    <div class="btn-toolbar">
        <?= Html::a(Yii::t('shop', '{icon} Back', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['index'], ['class' => 'btn btn-default']) ?>
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
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_SHOW_FILTERS, ['template' => '{input} {label} {error}'])->checkbox(['data-input' => 'checkbox', 'data-group' => 'general', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will hide/show the product filter in frontend'), 'class' => 'switch-input'], false);
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_INVOICE_DOWNLOAD, ['template' => '{input} {label} {error}'])->checkbox(['data-input' => 'checkbox', 'data-group' => 'general', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will enable/disable invoice download in order summary'), 'class' => 'switch-input'], false);
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_SHIPPING_LINK, ['template' => '{input} {label} {error}'])->checkbox(['data-input' => 'checkbox', 'data-group' => 'general', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will enable/disable shipping link in order summary'), 'class' => 'switch-input'], false);
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_SHORT_ORDER_ID, ['template' => '{input} {label} {error}'])->checkbox(['data-input' => 'checkbox', 'data-group' => 'general', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will enable/disable short order ids'), 'class' => 'switch-input'], false);
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_SHOW_OUT_OF_STOCK_VARIANTS, ['template' => '{input} {label} {error}'])->checkbox(['data-input' => 'checkbox', 'data-group' => 'general', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will hide/show out of stock variants'), 'class' => 'switch-input'], false);
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_SHOP_SELLS_ADULT_PRODUCTS, ['template' => '{input} {label} {error}'])->checkbox(['data-input' => 'checkbox', 'data-group' => 'general', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will hide/show age verification in checkout'), 'class' => 'switch-input'], false);
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
                echo $form->field($setting, ShopSettings::SHOP_PRODUCT_FEW_AVAILABLE_WARNING)->input('number', ['data-input' => 'textfield', 'data-group' => 'product', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will change warning an logged in user will get if stock is low.')]);
                ActiveForm::end();
                ?>
            </div>
            <div class="overlay hidden"
                 data-overlay="product"><?= FA::icon(FA::_SPINNER, ['class' => 'fa-spin']) ?></div>
        </div>

        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Checkout Settings') ?></h3>
            </div>
            <div class="box-body">
                <?php
                $form = ActiveForm::begin();
                echo $form->field($setting, ShopSettings::SHOP_GENERAL_ENABLE_DISCOUNT_CODES, ['template' => '{input} {label} {error}'])->checkbox(['data-input' => 'checkbox', 'data-group' => 'checkout', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will hide/show the discount code in frontend'), 'class' => 'switch-input'], false);
                ActiveForm::end();
                ?>
                <!--                <p>shop.checkout.viaPrepayment - bool</p>-->
                <!--                <p>shop.checkout.viaPrepayment - bool</p>-->
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
                echo $form->field($setting, ShopSettings::SHOP_INVOICE_LOGO)->textInput(['data-input' => 'textfield', 'data-group' => 'invoice', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will the invoice header logo image.')]);
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
                echo $form->field($setting, ShopSettings::SHOP_MAIL_LOGO)->textInput(['data-input' => 'textfield', 'data-group' => 'mail', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will the mail header logo image.')]);
                ActiveForm::end();
                ?>
                <h4><?= Yii::t('shop', 'Confirm Mail') ?></h4>
                <?php
                $form = ActiveForm::begin();
                echo $form->field($setting, ShopSettings::SHOP_MAIL_CONFIRM_BCC)->textInput(['data-input' => 'textfield', 'data-group' => 'mail', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will change bbc email(s) for the confirm mail.')]);
                echo $form->field($setting, ShopSettings::SHOP_MAIL_CONFIRM_REPLY_TO)->input('email', ['data-input' => 'textfield', 'data-group' => 'mail', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will change reply email for the confirm mail.')]);
                echo $form->field($setting, ShopSettings::SHOP_MAIL_CONFIRM_SUBJECT)->textInput(['data-input' => 'textfield', 'data-group' => 'mail', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will change the subject for the confirm mail.')]);
                ActiveForm::end();
                ?>
                <p></p>
                <h4><?= Yii::t('shop', 'Info Mail') ?></h4>
                <?php
                $form = ActiveForm::begin();
                echo $form->field($setting, ShopSettings::SHOP_MAIL_INFO_REPLY_TO)->input('email', ['data-input' => 'textfield', 'data-group' => 'mail', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will change reply email for the info mail.')]);
                echo $form->field($setting, ShopSettings::SHOP_MAIL_INFO_SUBJECT)->textInput(['data-input' => 'textfield', 'data-group' => 'mail', 'data-confirm-text' => Yii::t('shop', 'Are you sure you want to change this setting? This will change the subject for the info mail.')]);
                ActiveForm::end();
                ?>
            </div>
            <div class="overlay hidden"
                 data-overlay="mail"><?= FA::icon(FA::_SPINNER, ['class' => 'fa-spin']) ?></div>
        </div>
    </div>
</div>

