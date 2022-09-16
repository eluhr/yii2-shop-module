<?php
/**
 * --- FROM CONTEXT ---
 *
 * @var yii\web\View $this
 * @var \eluhr\shop\models\OrderSearch $filterModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use hrzg\widget\widgets\Cell;
use yii\widgets\ListView;

?>
<div class="order-all">
    <div class="container">
        <h2><?php echo \Yii::t('shop', 'Your orders') ?></h2>
        <?php echo Cell::widget(['id' => 'orders-all-top']); ?>
        <div class="row">
            <div class="col-xs-12 col-md-8 col-lg-6">
                <?php echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_order',
                    'layout' => "{items}\n{summary}\n{pager}",
                    'emptyText' => Yii::t('shop','No orders found')
                ]) ?>
            </div>
        </div>
        <?php echo Cell::widget(['id' => 'orders-all-bottom']); ?>
    </div>
</div>
