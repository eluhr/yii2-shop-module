<?php

use eluhr\shop\models\Order;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var View $this
 * @var Order $order
 */
?>
<div class="form-group">
    <?= Html::a(Yii::t('shop', '{icon} Back', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['orders'], ['class' => 'btn btn-default']); ?>
    <?= Html::a(Yii::t('shop', '{icon} Delete', ['icon' => FA::icon(FA::_TRASH_O)]), ['delete-order','id' => $order->id], ['class' => 'btn btn-danger pull-right','data-confirm' => Yii::t('shop', 'Are you sure? This action cannot be reverted! Order and Invoice will be deleted.')]); ?>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <h3><?= Yii::t('shop', 'Rechnungsadresse') ?></h3>
        <label><?= Yii::t('shop', 'First Name and Surname') ?></label>
        <p><?= $order->first_name ?> <?= $order->surname ?></p>
        <p>
            <?= $order->street_name ?> <?= $order->house_number ?>
            <br>
            <?= $order->postal ?> <?= $order->city ?>
        </p>
        <label><?= Yii::t('shop', 'Contact E-Mail') ?></label>
        <p><a href="mailto:<?= $order->email ?>"><?= $order->email ?></a></p>

        <label><?= Yii::t('shop', 'Order placed date') ?></label>
        <p><?= date('d.m.Y H:i', strtotime($order->created_at)) ?></p>

        <?php if (!empty($order->date_of_birth)): ?>
            <label><?= Yii::t('shop', 'Date of birth') ?></label>
            <p><?= date('d.m.Y', strtotime($order->date_of_birth)) ?></p>
        <?php endif; ?>
    </div>
    <?php if ($order->has_different_delivery_address): ?>
    <div class="col-xs-12 col-md-6">
        <h3><?= Yii::t('shop', 'Lieferadresse') ?></h3>
        <label><?= Yii::t('shop', 'First Name and Surname') ?></label>
        <p><?= $order->delivery_first_name ?> <?= $order->delivery_surname ?></p>
        <p>
            <?= $order->delivery_street_name ?> <?= $order->delivery_house_number ?>
            <br>
            <?= $order->delivery_postal ?> <?= $order->delivery_city ?>
        </p>
    </div>
    <?php endif; ?>
    <div class="col-xs-12 col-md-8">
        <?= $this->render('../shopping-cart/order', ['order' => $order, 'showCells' => false]) ?>
        <p><?= Yii::t('shop', 'Bezahlung via {type}', ['type' => $order::optsType()[$order->type] ?? '-']) ?></p>
        <?php
        if ($order->type === Order::TYPE_PAYPAL && $order->is_executed !== 1) {
            echo Html::a(Yii::t('shop', 'Confirm payment'), ['confirm-order', 'orderId' => $order->id], ['class' => 'btn btn-info']);
        }
        ?>
    </div>
    <div class="col-xs-12 col-md-4">
        <?php
        $form = ActiveForm::begin(['action' => ['save-order-internal-notes', 'id' => $order->id]]);
        echo $form->field($order, 'internal_notes')->textarea(['rows' => 4]);
        echo Html::submitButton(Yii::t('shop', '{icon} Save', ['icon' => FA::icon(FA::_SAVE)]), ['class' => 'btn btn-primary']);
        ActiveForm::end();
        ?>
    </div>
</div>

