<?php

use hrzg\widget\widgets\Cell;
use eluhr\shop\models\Order;
use eluhr\shop\models\ShoppingCartCheckout;
use eluhr\shop\models\ShopSettings;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use zhuravljov\yii\widgets\DatePicker;

/**
 * @var ShoppingCartCheckout $shoppingCartCheckout
 * @var View $this
 */
?>

<?= Cell::widget(['id' => 'shopping-cart-checkout-top']) ?>
    <div class="shopping-cart-view shopping-cart-checkout-view">
        <?php if (!Yii::$app->shoppingCart->hasReachedMinValue()): ?>
            <div class="alert alert-danger"><?php echo Yii::t('shop', 'In order to continue you must reach the minimum shopping cart limit of {value}', [
                    'value' => Yii::$app->formatter->asCurrency(ShopSettings::shopGeneralMinShoppingCartValue(), Yii::$app->payment->currency)
                ])?></div>
        <?php endif; ?>
        <?php

        echo $this->render('_table');


        $form = ActiveForm::begin(['id' => 'checkout-form']);


        echo Cell::widget(['id' => 'shopping-cart-checkout-form-begin']);

        echo $form->field($shoppingCartCheckout,'_exception')->hiddenInput()->label(false);

        echo Html::beginTag('div', ['class' => 'row']);

        echo Html::beginTag('div', ['class' => 'col-xs-12 col-md-4']);
        echo $form->field($shoppingCartCheckout, 'first_name');
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'col-xs-12 col-md-4']);
        echo $form->field($shoppingCartCheckout, 'surname');
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'col-xs-12 col-md-4']);
        echo $form->field($shoppingCartCheckout, 'email')->input('email');
        echo Html::endTag('div');

        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'row']);

        echo Html::beginTag('div', ['class' => 'col-xs-12 col-md-4']);
        echo $form->field($shoppingCartCheckout, 'street_name');
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'col-xs-12 col-md-2']);
        echo $form->field($shoppingCartCheckout, 'house_number');
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'col-xs-12 col-md-2']);
        echo $form->field($shoppingCartCheckout, 'postal');
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'col-xs-12 col-md-4']);
        echo $form->field($shoppingCartCheckout, 'city');
        echo Html::endTag('div');

        echo $this->render('_different_delivery_address', ['form' => $form, 'shoppingCartCheckout' => $shoppingCartCheckout]);

        echo Html::beginTag('div', ['class' => 'col-xs-12']);
        $shoppingCartCheckout->type = Order::TYPE_PAYREXX;
        echo $form->field($shoppingCartCheckout, 'type')->hiddenInput()->label(false);
        echo Html::endTag('div');

        if (ShopSettings::shopGeneralShopSellsAdultProducts()) {
            echo Html::beginTag('div', ['class' => 'col-xs-12 col-md-4']);
            echo $form->field($shoppingCartCheckout, 'date_of_birth')->widget(DatePicker::class, [
                'clientOptions' => [
                    'format' => 'dd.mm.yyyy',
                    'language' => 'de',
                    'autoclose' => true,
                    'startView' => 3
                ]
            ]);
            echo Html::endTag('div');
        }

        if (ShopSettings::shopGeneralAllowCustomerDetails()) {
            echo Html::beginTag('div', ['class' => 'col-xs-12 col-md-4']);
            echo $form->field($shoppingCartCheckout, 'customer_details')->textarea([
                    'rows' => 3
            ]);
            echo Html::endTag('div');
        }


        echo Html::endTag('div');

        echo Cell::widget(['id' => 'shopping-cart-checkout-form-end']);

        echo Html::beginTag('div', ['class' => 'row']);

        echo Html::beginTag('div', ['class' => 'col-xs-12']);
        echo $form->field($shoppingCartCheckout, 'agb_and_gdpr', ['template' => "{input}{label}\n{error}"])->checkbox([], false)->label($shoppingCartCheckout->getAttributeHint('agb_and_gdpr'))->hint(false);
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'col-xs-12 col-md-6']);
        echo Html::submitButton(Yii::t('shop', 'Bestellung kostenpflichtig abschließen'), [
                'class' => 'btn btn-success',
            'data-loading-text' => Yii::t('shop', 'Please wait...'),
            'disabled' => !Yii::$app->shoppingCart->hasReachedMinValue(),
            'id' => 'submit-checkout'
        ]);
        echo Html::endTag('div');

        echo Html::endTag('div');
        ActiveForm::end();
        ?>
    </div>
<?= Cell::widget(['id' => 'shopping-cart-checkout-bottom']) ?>