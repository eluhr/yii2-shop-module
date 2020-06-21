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

CSS;

$this->registerCss($css);

$totalPrice = 0;
?>

<div id="content">
    <div class="order-content">
        <h1 class="heading bold"><?= Yii::t('shop', 'Hallo {firstName} {surname},', ['firstName' => $order->first_name, 'surname' => $order->surname]) ?></h1>
        <p class="informative-text-0"><?= Yii::t('shop', 'deine Bestellung wurde versendet und ist jetzt auf dem Weg zu dir.') ?></p>
        <div class="box">
            <p class="informative-text-1"><?= Yii::t('shop', 'Dein Bestellübersicht und deinen Bestellstatus inkl. Sendungsverfolgung kannst du jederzeit unter folgendem Link aufrufen:') ?></p>
            <a href="<?= $order->detailUrl ?>" class="download-link bolder"><?= $order->detailUrl ?></a>
        </div>
    </div>
</div>