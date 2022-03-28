<?php
/**
 * --- FROM CONTEXT ---
 *
 * @var yii\web\View $this
 * @var \eluhr\shop\models\Order $order
 */
?>
<div class="billing-address">
    <h4><?= Yii::t('shop', 'Billing address') ?></h4>
    <p>
        <?= $order->first_name ?> <?= $order->surname ?>
        <br>
        <?= $order->street_name ?> <?= $order->house_number ?>
        <br>
        <?= $order->postal ?> <?= $order->city ?>
    </p>
</div>
<?php if ($order->has_different_delivery_address): ?>
    <div class="delivery-address">
        <h4><?= Yii::t('shop', 'Delivery address') ?></h4>
        <p>
            <?= $order->delivery_first_name ?> <?= $order->delivery_surname ?>
            <br>
            <?= $order->delivery_street_name ?> <?= $order->delivery_house_number ?>
            <br>
            <?= $order->delivery_postal ?> <?= $order->delivery_city ?>
        </p>
    </div>
<?php endif; ?>
