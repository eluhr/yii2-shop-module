<?php
/**
 * @var View $this
 */

use eluhr\shop\controllers\ShoppingCartController;
use eluhr\shop\models\ShoppingCartModify;
use eluhr\shop\models\ShopSettings;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$discountPercent = 0;
?>
<table class="table">
    <thead>
    <tr>
        <th><?= Yii::t('shop', 'Produkt', [], 'de') ?></th>
        <th><?= Yii::t('shop', 'Preis', [], 'de') ?></th>
        <th><?= Yii::t('shop', 'Anzahl', [], 'de') ?></th>
        <th><?= Yii::t('shop', 'Gesamtpreis', [], 'de') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach (Yii::$app->shoppingCart->positions as $position) {
    if ($position->isDiscount === false) {
        echo $this->render('_table_row', ['position' => $position]);
    }
} ?>
    <?php foreach (Yii::$app->shoppingCart->positions as $position) {
    if ($position->isDiscount === true) {
        $discountPercent += $position->item()->percent;
        echo $this->render('_table_row', ['position' => $position]);
    }
}
    ?>
    <?php if (ShopSettings::shopProductShowShippingCosts() || Yii::$app->shoppingCart->shippingCost() > 0): ?>
    <tr>
        <td><?= Yii::t('shop', 'Versandkosten', [], 'de') ?></td>
        <td></td>
        <td></td>
        <td><?= Yii::$app->formatter->asCurrency(Yii::$app->shoppingCart->shippingCost(), Yii::$app->payment->currency) ?></td>
    </tr>
    <?php endif ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="3">
            <?= Yii::t('shop', 'Gesamtpreis', [], 'de') ?>
        </th>
        <th>
            <span class="total"
                  data-content="total"><?= Yii::$app->formatter->asCurrency(Yii::$app->shoppingCart->total(), Yii::$app->payment->currency) ?></span>
            <div class="discount" data-content="discount"></div>
        </th>
        <td></td>
    </tr>
    </tfoot>
</table>
