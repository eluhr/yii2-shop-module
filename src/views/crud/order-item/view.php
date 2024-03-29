<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\OrderItem $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('shop', 'Order Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop.plural', 'Order Item'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'order_id' => $model->order_id, 'variant_id' => $model->variant_id, 'extra_info' => $model->extra_info]];
$this->params['breadcrumbs'][] = Yii::t('shop', 'View');
?>
<div class="giiant-crud order-item-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?= Html::encode($model->name) ?>
        <small>
            <?= Yii::t('shop', 'Order Item') ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?php 
 echo Html::a(
            '<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('shop', 'Edit'),
            [ 'update', 'order_id' => $model->order_id, 'variant_id' => $model->variant_id, 'extra_info' => $model->extra_info],
            ['class' => 'btn btn-info'])
          ?>

            <?php 
 echo Html::a(
            '<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('shop', 'Copy'),
            ['create', 'order_id' => $model->order_id, 'variant_id' => $model->variant_id, 'extra_info' => $model->extra_info, 'OrderItem'=>$copyParams],
            ['class' => 'btn btn-success'])
          ?>

            <?php 
 echo Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('shop', 'New'),
            ['create'],
            ['class' => 'btn btn-success'])
          ?>
        </div>

        <div class="pull-right">
            <?= Html::a('<span class="glyphicon glyphicon-list"></span> '
            . Yii::t('shop', 'Full list'), ['index'], ['class'=>'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('eluhr\shop\models\OrderItem'); ?>

    
    <?php 
 echo DetailView::widget([
    'model' => $model,
    'attributes' => [
    // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::attributeFormat
[
    'format' => 'html',
    'attribute' => 'order_id',
    'value' => ($model->order ? 
        Html::a('<i class="glyphicon glyphicon-list"></i>', ['/shop/crud/order/index']).' '.
        Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->order->id, ['/shop/crud/order/view', 'id' => $model->order->id,]).' '.
        Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'OrderItem'=>['order_id' => $model->order_id]])
        : 
        '<span class="label label-warning">?</span>'),
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::attributeFormat
[
    'format' => 'html',
    'attribute' => 'variant_id',
    'value' => ($model->variant ? 
        Html::a('<i class="glyphicon glyphicon-list"></i>', ['/shop/crud/variant/index']).' '.
        Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->variant->label, ['/shop/crud/variant/view', 'id' => $model->variant->id,]).' '.
        Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'OrderItem'=>['variant_id' => $model->variant_id]])
        : 
        '<span class="label label-warning">?</span>'),
],
        'name',
        'quantity',
        'extra_info',
        'single_price',
        'single_net_price',
        'vat',
        'created_at',
        'updated_at',
        'configuration_json:ntext',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::attributeFormat
[
    'format' => 'html',
    'attribute' => 'configuration_id',
    'value' => ($model->configuration ? 
        Html::a('<i class="glyphicon glyphicon-list"></i>', ['/shop/crud/configuration/index']).' '.
        Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->configuration->id, ['/shop/crud/configuration/view', 'id' => $model->configuration->id,]).' '.
        Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'OrderItem'=>['configuration_id' => $model->configuration_id]])
        : 
        '<span class="label label-warning">?</span>'),
],
    ],
    ]);
  ?>

    
    <hr/>

    <?php 
 echo Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('shop', 'Delete'), ['delete', 'order_id' => $model->order_id, 'variant_id' => $model->variant_id, 'extra_info' => $model->extra_info],
    [
    'class' => 'btn btn-danger',
    'data-confirm' => '' . Yii::t('shop', 'Are you sure to delete this item?') . '',
    'data-method' => 'post',
    ]);
  ?>
    <?php $this->endBlock(); ?>


    
    <?php 
        echo Tabs::widget(
                 [
                     'id' => 'relation-tabs',
                     'encodeLabels' => false,
                     'items' => [
 [
    'label'   => '<b class=""># '.Html::encode($model->order_id).'</b>',
    'content' => $this->blocks['eluhr\shop\models\OrderItem'],
    'active'  => true,
],
 ]
                 ]
    );
    ?>
</div>
