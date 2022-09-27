<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\Setting $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('shop', 'Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop.plural', 'Setting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->key, 'url' => ['view', 'key' => $model->key]];
$this->params['breadcrumbs'][] = Yii::t('shop', 'View');
?>
<div class="giiant-crud setting-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?= Html::encode($model->key) ?>
        <small>
            <?= Yii::t('shop', 'Setting') ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?php 
 echo Html::a(
            '<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('shop', 'Edit'),
            [ 'update', 'key' => $model->key],
            ['class' => 'btn btn-info'])
          ?>

            <?php 
 echo Html::a(
            '<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('shop', 'Copy'),
            ['create', 'key' => $model->key, 'Setting'=>$copyParams],
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

    <?php $this->beginBlock('eluhr\shop\models\Setting'); ?>

    
    <?php 
 echo DetailView::widget([
    'model' => $model,
    'attributes' => [
            'key',
        'created_at',
        'updated_at',
        'value',
    ],
    ]);
  ?>

    
    <hr/>

    <?php 
 echo Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('shop', 'Delete'), ['delete', 'key' => $model->key],
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
    'label'   => '<b class=""># '.Html::encode($model->key).'</b>',
    'content' => $this->blocks['eluhr\shop\models\Setting'],
    'active'  => true,
],
 ]
                 ]
    );
    ?>
</div>
