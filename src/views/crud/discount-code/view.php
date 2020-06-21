<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\DiscountCode $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('shop', 'Discount Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Discount Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('shop', 'View');
?>
<div class="giiant-crud discount-code-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?= Yii::t('shop', 'Discount Code') ?>
        <small>
            <?= Html::encode($model->label) ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?= Html::a(
            '<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('shop', 'Edit'),
            [ 'update', 'id' => $model->id],
            ['class' => 'btn btn-info']) ?>

            <?= Html::a(
            '<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('shop', 'Copy'),
            ['create', 'id' => $model->id, 'DiscountCode'=>$copyParams],
            ['class' => 'btn btn-success']) ?>

            <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('shop', 'New'),
            ['create'],
            ['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">
            <?= Html::a('<span class="glyphicon glyphicon-list"></span> '
            . Yii::t('shop', 'Full list'), ['index'], ['class'=>'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('eluhr\shop\models\DiscountCode'); ?>

    
    <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
            'code',
        'percent',
        'expiration_date',
        'created_at',
        'updated_at',
        'used',
    ],
    ]); ?>

    
    <hr/>

    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('shop', 'Delete'), ['delete', 'id' => $model->id],
    [
    'class' => 'btn btn-danger',
    'data-confirm' => '' . Yii::t('shop', 'Are you sure to delete this item?') . '',
    'data-method' => 'post',
    ]); ?>
    <?php $this->endBlock(); ?>


    
<?php $this->beginBlock('Orders'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?= Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('shop', 'List All') . ' Orders',
            ['/shop/crud/order/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('shop', 'New') . ' Order',
            ['/shop/crud/order/create', 'Order' => ['discount_code_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Orders', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Orders ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}{pager}<br/>{items}{pager}',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getOrders(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-orders',
        ]
    ]),
    'pager'        => [
        'class'          => yii\widgets\LinkPager::className(),
        'firstPageLabel' => Yii::t('shop', 'First'),
        'lastPageLabel'  => Yii::t('shop', 'Last')
    ],
    'columns' => [
 [
    'class'      => 'yii\grid\ActionColumn',
    'template'   => '{view} {update}',
    'contentOptions' => ['nowrap'=>'nowrap'],
    'urlCreator' => function ($action, $model, $key, $index) {
        // using the column name as key, not mapping to 'id' like the standard generator
        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
        $params[0] = '/shop/crud/order' . '/' . $action;
        $params['Order'] = ['discount_code_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => '/shop/crud/order'
],
        'id',
        'paypal_id',
        'paypal_token',
        'paypal_payer_id',
        'is_executed',
        'date_of_birth',
        'internal_notes:ntext',
        'info_mail_has_been_sent',
        'first_name',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


    <?= Tabs::widget(
                 [
                     'id' => 'relation-tabs',
                     'encodeLabels' => false,
                     'items' => [
 [
    'label'   => '<b class=""># '.Html::encode($model->id).'</b>',
    'content' => $this->blocks['eluhr\shop\models\DiscountCode'],
    'active'  => true,
],
[
    'content' => $this->blocks['Orders'],
    'label'   => '<small>Orders <span class="badge badge-default">'. $model->getOrders()->count() . '</span></small>',
    'active'  => false,
],
 ]
                 ]
    );
    ?>
</div>
