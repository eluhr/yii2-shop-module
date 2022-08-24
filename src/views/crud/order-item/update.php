<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\OrderItem $model
*/

$this->title = Yii::t('shop', 'Order Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Order Item'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'order_id' => $model->order_id, 'variant_id' => $model->variant_id, 'extra_info' => $model->extra_info]];
$this->params['breadcrumbs'][] = Yii::t('shop', 'Edit');
?>
<div class="giiant-crud order-item-update">

    <h1>
                <?= Html::encode($model->name) ?>

        <small>
            <?= Yii::t('shop', 'Order Item') ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('shop', 'View'), ['view', 'order_id' => $model->order_id, 'variant_id' => $model->variant_id, 'extra_info' => $model->extra_info], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
