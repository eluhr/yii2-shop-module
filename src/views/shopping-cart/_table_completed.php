<?php
/**
 * @var View $this
 * @var Order $order
 */

use eluhr\shop\models\Order;
use eluhr\shop\models\ShopSettings;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\web\View;

?>
<table class="table">
    <thead>
    <tr>
        <th><?= Yii::t('shop', 'Produkt') ?></th>
        <th><?= Yii::t('shop', 'Preis') ?></th>
        <th><?= Yii::t('shop', 'Anzahl') ?></th>
        <th><?= Yii::t('shop', 'Gesamtpreis') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $total = 0;
    foreach ($order->orderItems as $position):
        $subTotal = $position->single_price * $position->quantity;
        $total += $subTotal;
        ?>
        <tr>
            <td>
                <?= Html::tag('span', $position->name . ($position->extra_info !== '-' ? ' - ' . $position->extra_info : ''), ['class' => 'product-name']); ?>
            </td>
            <td>
                <?= Yii::$app->formatter->asCurrency($position->single_price, Yii::$app->payment->currency) ?>
            </td>
            <td>
                <div class="btn-toolbar">
                    <div class="btn">
                        <?= Html::tag('span', $position->quantity, ['class' => 'quantity']) ?>
                    </div>
                </div>
            </td>
            <td>
                <?= Yii::$app->formatter->asCurrency($subTotal, Yii::$app->payment->currency) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if ($order->discount_code_id !== null): ?>
        <tr>
            <td><?= $order->discountCode->label ?></td>
            <td></td>
            <td></td>
            <td><?= '-' . $order->discountCode->prettyPercent() ?></td>
        </tr>
        <?php
        $percent = 1 * ($order->discountCode->percent / 100);
        $total += $total * $percent * -1;
        ?>
    <?php endif ?>
    <?php if (ShopSettings::shopProductShowShippingCosts() || $order->shipping_price > 0): ?>
    <tr>
        <td><?= Yii::t('shop', 'Versandkosten', [], 'de') ?></td>
        <td></td>
        <td></td>
        <td><?= Yii::$app->formatter->asCurrency($order->shipping_price, Yii::$app->payment->currency) ?></td>
    </tr>
    <?php
    endif;
    $total += $order->shipping_price;
    ?>
    </tbody>
    <tfoot>
    <tr class="total-pice">
        <th colspan="3">
            <?= Yii::t('shop', 'Gesamtpreis') ?>
        </th>
        <th>
            <?= Yii::$app->formatter->asCurrency($total, Yii::$app->payment->currency) ?>
        </th>
        <td></td>
    </tr>
    <tr class="order-number">
        <th colspan="3">
            <?= Yii::t('shop', 'Bestellnummer', [], 'de') ?>
        </th>
        <th>
            <?= $order->id ?>
        </th>
        <td></td>
    </tr>
    <tr class="order-status">
        <th colspan="3">
            <?= Yii::t('shop', 'Bestellstatus', [], 'de') ?>
        </th>
        <th>
            <?= $order->statusLabel ?>
        </th>
        <td></td>
    </tr>
    <?php if (!empty($order->shipment_link) && ShopSettings::shopGeneralShippingLink()): ?>
    <tr class="order-status">
        <th colspan="3">
            <?= Yii::t('shop', 'Sendungsverfolgungslink', [], 'de') ?>
        </th>
        <th>
            <?= Html::a($order->shipment_link, $order->shipment_link, ['target' => '_blank']) ?>
        </th>
        <td></td>
    </tr>
    <?php endif; ?>
    <?php if (!empty($order->invoice_number) && ShopSettings::shopGeneralInvoiceDownload()): ?>
    <tr class="order-status">
        <th colspan="3">
            <?= Yii::t('shop', 'Rechnung', [], 'de') ?>
        </th>
        <th>
            <?= Html::a(Yii::t('shop', '{icon} Herunterladen', ['icon' => FA::icon(FA::_DOWNLOAD)], 'de'), $order->invoiceUrl, ['class' => 'btn btn-primary']) ?>
        </th>
        <td></td>
    </tr>
    <?php endif; ?>
    </tfoot>
</table>
