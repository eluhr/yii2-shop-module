<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @var Order $order
 */

use eluhr\shop\models\Order;

$css = <<<CSS
body {
margin: 0;
padding: 0;
background-color: #f3f2f0;
}
.informative-text {
    padding: 0 2em;
    margin: 0 2em;
}

.heading {
    font-size: 2em;
}

.bold {
    font-weight: 400;
}

.bolder {
    font-weight: 800;
}

a {
    color: #000000;
    text-decoration: none;
}

.box {
    padding: 1.5em;
    background-color: #f3f2f0;
}

.informative-text-1 {
    margin-top: 0;
}

.informative-text-2 {
    padding-bottom: 2em;
}

table {
    width: 100%;
    font-size: 16px;
}

td,th {
    padding: 8px;
    font-size: inherit;
    font-weight: inherit;
    vertical-align: top;
    text-align: left;
}

th {
    font-weight: 900;
}

.total-price {
    text-align: right;
}

.informative-text {
    text-align: center;
    margin-bottom: 2em;
    padding-bottom: 2em;
}

.last-pad {
    padding-bottom: 2em;
}

.first-pad {
    padding-top: 2em;
}

.no-mar {
 margin: 0;
}
.currency {
    text-align: right;
}

CSS;

$this->registerCss($css);

$totalPrice = 0;
?>

<div id="content">
    <div class="order-content">
        <h1 class="heading bold"><?= Yii::t('shop', 'Hallo {firstName} {surname},', ['firstName' => $order->first_name, 'surname' => $order->surname]) ?></h1>
        <p class="informative-text-0"><?= Yii::t('shop', 'vielen Dank für deine Bestellung bei den Caddyroamers.') ?></p>
        <div class="box">
            <p class="informative-text-1"><?= Yii::t('shop', 'Dein Bestellübersicht und deinen Bestellstatus inkl. Sendungsverfolgung kannst du jederzeit unter folgendem Link aufrufen:') ?></p>
            <a href="<?= $order->detailUrl ?>" class="download-link bolder"><?= $order->detailUrl ?></a>
        </div>
        <h2 class="heading bold"><?= Yii::t('shop', 'Bestellübersicht') ?></h2>
        <div class="box">
            <table>
                <tr>
                    <th>
                        <?= Yii::t('shop', 'Produkt') ?>
                    </th>
                    <th class="currency">
                        <?= Yii::t('shop', 'Preis') ?>
                    </th>
                    <th>
                        <?= Yii::t('shop', 'Anzahl') ?>
                    </th>
                    <th class="currency">
                        <?= Yii::t('shop', 'Gesamtpreis') ?>
                    </th>
                </tr>
                <?php foreach ($order->orderItems as $orderItem): ?>
                    <?php $subTotal = $orderItem->single_price * $orderItem->quantity ?>
                    <tr>
                        <td>
                            <?= $orderItem->name ?>
                        </td>
                        <td class="currency">
                            <?= Yii::$app->formatter->asCurrency($orderItem->single_price, Yii::$app->payment->currency) ?>
                        </td>
                        <td>
                            <?= $orderItem->quantity ?>
                        </td>
                        <td class="currency">
                            <?= Yii::$app->formatter->asCurrency($subTotal, Yii::$app->payment->currency) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                <?php if ($order->discount_code_id !== null): ?>
                <tr>
                    <td><?= $order->discountCode->label ?></td>
                    <td></td>
                    <td></td>
                    <td class="currency"><?= '-' . $order->discountCode->prettyPercent() ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td><?= Yii::t('shop', 'Versandkosten', [], 'de') ?></td>
                    <td></td>
                    <td></td>
                    <td class="currency"><?= Yii::$app->formatter->asCurrency($order->shipping_price, Yii::$app->payment->currency) ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="currency">
                        <p class="total-price bolder"><?= Yii::$app->formatter->asCurrency($order->totalAmount, Yii::$app->payment->currency)?></p>
                    </td>
                </tr>
            </table>
        </div>

        <?php if ($order->type === $order::TYPE_PAYPAL): ?>
            <p class="informative-text-2 bolder"><?= Yii::t('shop', 'Deine Bestellung wurde per PayPal beglichen.') ?></p>
        <?php else: ?>
            <p class="informative-text-2 bolder"><?= Yii::t('shop', 'Die Bezahlung deiner Bestellung ist noch offen. Bitte überweise den vollen Betrag auf folgendes Konto: ') ?></p>
            <p class="no-mar"><?= Yii::t('shop', 'Beck, Bourgeret und Feller GbR') ?></p>
            <p class="no-mar"><?= Yii::t('shop', 'IBAN: DE74 3905 0000 1073 5058 18') ?></p>
            <p class="no-mar"><?= Yii::t('shop', 'BIC: AACSDE33XXX') ?></p>
            <p class="no-mar last-pad"><?= Yii::t('shop', 'Sparkasse Aachen') ?></p>
        <?php endif; ?>
    </div>
</div>