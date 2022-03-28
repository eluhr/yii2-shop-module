<?php
/**
 * --- FROM CONTEXT ---
 *
 * @var yii\web\View $this
 * @var \eluhr\shop\models\Order $model
 */

use yii\helpers\Html;

?>
<div class="order-item">
    <div class="order-item-attribute" data-attribute="order-id">
        <span class="order-item-label"><?php echo Yii::t('shop', 'Order') ?></span>
        <span class="order-item-value"><?php echo Html::a($model->id, ['detail','orderId' => $model->id], ['class' => 'btn btn-link']) ?></span>
    </div>
    <div class="order-item-attribute" data-attribute="date">
        <span class="order-item-label"><?php echo Yii::t('shop', 'Date') ?></span>
        <span class="order-item-value"><?php echo Yii::t('shop', '{dateTime} Uhr', [
                'dateTime' => Yii::$app->getFormatter()->asDatetime($model->created_at, 'short')
            ]) ?></span>
    </div>
    <div class="order-item-attribute" data-attribute="status">
        <span class="order-item-label"><?php echo Yii::t('shop', 'Status') ?></span>
        <span class="order-item-value"><?php echo $model->statusLabel ?></span>
    </div>
    <div class="order-item-attribute" data-attribute="total">
        <span class="order-item-label"><?php echo Yii::t('shop', 'Gesamtkosten') ?></span>
        <span class="order-item-value"><?php echo Yii::$app->getFormatter()->asCurrency($model->totalAmount) ?></span>
    </div>
</div>