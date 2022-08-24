<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\TagXProduct $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('shop', 'Tag X Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop.plural', 'Tag X Product'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->tag_id, 'url' => ['view', 'tag_id' => $model->tag_id, 'product_id' => $model->product_id]];
$this->params['breadcrumbs'][] = Yii::t('shop', 'View');
?>
<div class="giiant-crud tag-x-product-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?= Html::encode($model->tag_id) ?>
        <small>
            <?= Yii::t('shop', 'Tag X Product') ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?php 
 echo Html::a(
            '<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('shop', 'Edit'),
            [ 'update', 'tag_id' => $model->tag_id, 'product_id' => $model->product_id],
            ['class' => 'btn btn-info'])
          ?>

            <?php 
 echo Html::a(
            '<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('shop', 'Copy'),
            ['create', 'tag_id' => $model->tag_id, 'product_id' => $model->product_id, 'TagXProduct'=>$copyParams],
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

    <?php $this->beginBlock('eluhr\shop\models\TagXProduct'); ?>

    
    <?php 
 echo DetailView::widget([
    'model' => $model,
    'attributes' => [
    // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::attributeFormat
[
    'format' => 'html',
    'attribute' => 'tag_id',
    'value' => ($model->tag ? 
        Html::a('<i class="glyphicon glyphicon-list"></i>', ['/shop/crud/tag/index']).' '.
        Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->tag->name, ['/shop/crud/tag/view', 'id' => $model->tag->id,]).' '.
        Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'TagXProduct'=>['tag_id' => $model->tag_id]])
        : 
        '<span class="label label-warning">?</span>'),
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::attributeFormat
[
    'format' => 'html',
    'attribute' => 'product_id',
    'value' => ($model->product ? 
        Html::a('<i class="glyphicon glyphicon-list"></i>', ['/shop/crud/product/index']).' '.
        Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->product->title, ['/shop/crud/product/view', 'id' => $model->product->id,]).' '.
        Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'TagXProduct'=>['product_id' => $model->product_id]])
        : 
        '<span class="label label-warning">?</span>'),
],
        'created_at',
        'updated_at',
    ],
    ]);
  ?>

    
    <hr/>

    <?php 
 echo Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('shop', 'Delete'), ['delete', 'tag_id' => $model->tag_id, 'product_id' => $model->product_id],
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
    'label'   => '<b class=""># '.Html::encode($model->tag_id).'</b>',
    'content' => $this->blocks['eluhr\shop\models\TagXProduct'],
    'active'  => true,
],
 ]
                 ]
    );
    ?>
</div>
