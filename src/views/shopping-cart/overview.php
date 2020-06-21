<?php

use hrzg\widget\widgets\Cell;
use eluhr\shop\models\ShoppingCartDiscount;
use eluhr\shop\models\ShopSettings;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var ShoppingCartDiscount $shoppingCartDiscount
 * @var View $this
 */
?>

<?= Cell::widget(['id' => 'shopping-cart-overview-top'])?>
<div class="shopping-cart-view shopping-cart-overview-view">
    <?= $this->render('_table') ?>
    <?php
    if (ShopSettings::shopGeneralEnableDiscountCodes()) {
        $form = ActiveForm::begin(['id' => 'overview-form']);

        if ($shoppingCartDiscount->isActive()) {
            $submitButton = Html::submitButton(FA::icon(FA::_CHECK), ['class' => 'btn btn-check-discount-code btn-success disabled','disabled' => 'disabled']);
        } else {
            $submitButton = Html::submitButton(Yii::t('shop', 'Apply Discount Code'), ['class' => 'btn btn-check-discount-code btn-info']);
        }

        echo $form->field($shoppingCartDiscount, 'discount_code', [
            'template' => "{label}\n<div class='input-group'>{input}<span class='input-group-btn'>{$submitButton}</span></div>\n{hint}\n{error}",
        ])->textInput(['readonly' => $shoppingCartDiscount->isActive()]);
        ActiveForm::end();
    }
    ?>

    <?=Html::a(Yii::t('shop', 'Continue to checkout'), ['checkout'], ['class' => 'btn btn-success'])?>
</div>
<?= Cell::widget(['id' => 'shopping-cart-overview-bottom'])?>