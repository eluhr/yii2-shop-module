<?php

use eluhr\shop\helpers\RbacHelper;
use hrzg\widget\widgets\Cell;
use yii\helpers\Html;

/**
 * --- FROM CONTEXT ---
 *
 * @var yii\web\View $this
 * @var \eluhr\shop\models\Order $model
 * @var bool $showCells
 */
$isAllowedToViewShippingDetails = $model->isOwn() || RbacHelper::userIsShopEditor();
?>
<div class="order-view">
    <div class="container">
        <?php if($isAllowedToViewShippingDetails): ?>
        <div class="form-group">
            <?php echo Html::a(\Yii::t('shop', 'Back to my orders'), ['all'],['class' => 'btn btn-back btn-primary'])?>
        </div>
        <?php endif ?>
        <h2><?php echo \Yii::t('shop', 'Order {orderId}', [
                'orderId' => $model->id
            ]) ?></h2>
        <?php echo Cell::widget(['id' => 'orders-detail-top']); ?>
        <div class="row">
            <div class="col-xs-12 <?php echo $isAllowedToViewShippingDetails ? 'col-md-8' : ''?>">
                <?php echo $this->render('../shopping-cart/_table_completed', ['order' => $model]); ?>
            </div>
            <?php if($isAllowedToViewShippingDetails):?>
            <div class="col-xs-12 col-md-4">
                <?php echo $this->render('_shipping-details', ['order' => $model]); ?>
                <div class="form-group">
                    <?php echo Html::a(Yii::t('shop','Order again'),['/' . $this->context->module->id . '/orders/again','orderId' => $model->id], ['class' => 'btn btn-primary'])?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php echo Cell::widget(['id' => 'orders-detail-bottom']); ?>
    </div>
</div>
