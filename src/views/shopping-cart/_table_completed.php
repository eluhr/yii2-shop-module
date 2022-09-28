<?php
/**
 * @var View $this
 * @var Order $order
 */

use eluhr\shop\models\DiscountCode;
use eluhr\shop\models\Order;
use eluhr\shop\models\ShopSettings;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

?>
<table class="table">
    <thead>
    <tr>
        <th><?= Yii::t('shop', 'Produkt') ?></th>
        <th><?= Yii::t('shop', 'Preis') ?></th>
        <th><?= Yii::t('shop', 'Anzahl') ?></th>
        <th><?= Yii::t('shop', 'Preis') ?></th>
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
            <td>
                <?php foreach ($order->orderItems as $orderItem): ?>
                    <?php
                    $configuratorData = json_decode($orderItem->configuration_json);
                    $variant = \eluhr\shop\models\Variant::find()->where(['id' => $configuratorData->variantId])->one();
                    if (isset($variant) && $variant->getIsConfigurable()) {
                        echo Html::a(Yii::t('shop', 'Review Product Configuration'),
                            Url::to("$variant->configurator_url"),
                            [
                                'class' => 'review-product-configuration',
                                'target' => '_blank',
                                'data' => [
                                    'method' => 'POST',
                                    'params' => [
                                        'configurator_data' => json_encode($configuratorData),
                                        'read_only' => true
                                    ]
                                ]
                            ]);
                    }
                    ?>
                <?php endforeach; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if ($order->discount_code_id !== null): ?>
        <tr>
            <td><?= $order->discountCode->label ?></td>
            <td></td>
            <td></td>
            <td><?= '-' . $order->discountCode->prettyValue() ?></td>
        </tr>
        <?php
        if ($order->discountCode->type === DiscountCode::TYPE_PERCENT) {
            $percent = 1 * ($order->discountCode->value / 100);
            $total += $total * $percent * -1;
        } else if ($order->discountCode->type === DiscountCode::TYPE_AMOUNT) {
            $total -= $order->discountCode->value;
        }

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
            <?= Yii::t('shop', 'Gesamtsumme') ?>
        </th>
        <th>
            <?= Yii::$app->formatter->asCurrency($total, Yii::$app->payment->currency) ?>
        </th>
    </tr>
    <tr class="order-number">
        <th colspan="3">
            <?= Yii::t('shop', 'Bestellnummer', [], 'de') ?>
        </th>
        <th>
            <?= $order->id ?>
        </th>
    </tr>
    <tr class="order-status">
        <th colspan="3">
            <?= Yii::t('shop', 'Bestellstatus', [], 'de') ?>
        </th>
        <th>
            <?= $order->statusLabel ?>
        </th>
    </tr>
    <?php if (!empty($order->shipment_link) && ShopSettings::shopGeneralShippingLink()): ?>
    <tr class="order-status">
        <th colspan="3">
            <?= Yii::t('shop', 'Sendungsverfolgungslink', [], 'de') ?>
        </th>
        <th>
            <?= Html::a($order->shipment_link, $order->shipment_link, ['target' => '_blank']) ?>
        </th>
    </tr>
    <?php endif; ?>
    <?php if (ShopSettings::shopGeneralAllowCustomerDetails()): ?>
    <tr class="order-customer-details">
        <th colspan="3">
            <?= Yii::t('shop', 'Customer Details', [], 'de') ?>
        </th>
        <th>
            <?= Html::encode($order->customer_details) ?>
        </th>
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
    </tr>
    <?php endif; ?>
    </tfoot>
</table>
