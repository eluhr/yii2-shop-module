<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\Product $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('shop', 'Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('shop', 'View');
?>
<div class="giiant-crud product-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?= Yii::t('shop', 'Product') ?>
        <small>
            <?= Html::encode($model->title) ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?= Html::a(
    '<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('shop', 'Edit'),
    [ 'update', 'id' => $model->id],
    ['class' => 'btn btn-info']
) ?>

            <?= Html::a(
    '<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('shop', 'Copy'),
    ['create', 'id' => $model->id, 'Product'=>$copyParams],
    ['class' => 'btn btn-success']
) ?>

            <?= Html::a(
    '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('shop', 'New'),
    ['create'],
    ['class' => 'btn btn-success']
) ?>
        </div>

        <div class="pull-right">
            <?= Html::a('<span class="glyphicon glyphicon-list"></span> '
            . Yii::t('shop', 'Full list'), ['index'], ['class'=>'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('eluhr\shop\models\Product'); ?>

    
    <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        ],
    ]); ?>

    
    <hr/>

    <?= Html::a(
        '<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('shop', 'Delete'),
        ['delete', 'id' => $model->id],
        [
    'class' => 'btn btn-danger',
    'data-confirm' => '' . Yii::t('shop', 'Are you sure to delete this item?') . '',
    'data-method' => 'post',
    ]
    ); ?>
    <?php $this->endBlock(); ?>


    
<?php $this->beginBlock('FirstVariant'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?= Html::a(
        '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('shop', 'List All') . ' First Variant',
        ['/shop/crud/variant/index'],
        ['class'=>'btn text-muted btn-xs']
    ) ?>
  <?= Html::a(
        '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('shop', 'New') . ' First Variant',
        ['/shop/crud/variant/create', 'Variant' => ['product_id' => $model->id]],
        ['class'=>'btn btn-success btn-xs']
    ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-FirstVariant', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-FirstVariant ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}{pager}<br/>{items}{pager}',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getFirstVariant(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-firstvariant',
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
        $params[0] = '/shop/crud/variant' . '/' . $action;
        $params['Variant'] = ['product_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => '/shop/crud/variant'
],
        'id',
        'title',
        'thumbnail_image',
        'is_online',
        'rank',
        'price',
        'hex_color',
        'stock',
        'sku',
]
])
 . '</div>'
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('ActiveVariants'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?= Html::a(
    '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('shop', 'List All') . ' Active Variants',
    ['/shop/crud/variant/index'],
    ['class'=>'btn text-muted btn-xs']
) ?>
  <?= Html::a(
    '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('shop', 'New') . ' Active Variant',
    ['/shop/crud/variant/create', 'Variant' => ['product_id' => $model->id]],
    ['class'=>'btn btn-success btn-xs']
); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-ActiveVariants', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-ActiveVariants ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}{pager}<br/>{items}{pager}',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getActiveVariants(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-activevariants',
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
        $params[0] = '/shop/crud/variant' . '/' . $action;
        $params['Variant'] = ['product_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => '/shop/crud/variant'
],
        'id',
        'title',
        'thumbnail_image',
        'is_online',
        'rank',
        'price',
        'hex_color',
        'stock',
        'sku',
]
])
 . '</div>'
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Tags'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?= Html::a(
    '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('shop', 'List All') . ' Tags',
    ['/shop/crud/tag/index'],
    ['class'=>'btn text-muted btn-xs']
) ?>
  <?= Html::a(
    '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('shop', 'New') . ' Tag',
    ['/shop/crud/tag/create', 'Tag' => ['id' => $model->id]],
    ['class'=>'btn btn-success btn-xs']
); ?>
  <?= Html::a(
    '<span class="glyphicon glyphicon-link"></span> ' . Yii::t('shop', 'Attach') . ' Tag',
    ['/shop/crud/tag-x-product/create', 'TagXProduct'=>['product_id'=>$model->id]],
    ['class'=>'btn btn-info btn-xs']
) ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Tags', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Tags ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}{pager}<br/>{items}{pager}',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getTagXProducts(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-tagxproducts',
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
    'template'   => '{view} {delete}',
    'contentOptions' => ['nowrap'=>'nowrap'],
    'urlCreator' => function ($action, $model, $key, $index) {
        // using the column name as key, not mapping to 'id' like the standard generator
        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
        $params[0] = '/shop/crud/tag-x-product' . '/' . $action;
        $params['TagXProduct'] = ['product_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        'delete' => function ($url, $model) {
            return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, [
                    'class' => 'text-danger',
                    'title'         => Yii::t('shop', 'Remove'),
                    'data-confirm'  => Yii::t('shop', 'Are you sure you want to delete the related item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]);
        },
'view' => function ($url, $model) {
    return Html::a(
        '<span class="glyphicon glyphicon-cog"></span>',
        $url,
        [
                        'data-title'  => Yii::t('shop', 'View Pivot Record'),
                        'data-toggle' => 'tooltip',
                        'data-pjax'   => '0',
                        'class'       => 'text-muted',
                    ]
    );
},
    ],
    'controller' => '/shop/crud/tag-x-product'
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'tag_id',
    'value' => function ($model) {
        if ($rel = $model->tag) {
            return Html::a($rel->name, ['/shop/crud/tag/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'created_at',
        'updated_at',
]
])
 . '</div>'
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Variants'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?= Html::a(
    '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('shop', 'List All') . ' Variants',
    ['/shop/crud/variant/index'],
    ['class'=>'btn text-muted btn-xs']
) ?>
  <?= Html::a(
    '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('shop', 'New') . ' Variant',
    ['/shop/crud/variant/create', 'Variant' => ['product_id' => $model->id]],
    ['class'=>'btn btn-success btn-xs']
); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Variants', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Variants ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}{pager}<br/>{items}{pager}',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getVariants(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-variants',
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
        $params[0] = '/shop/crud/variant' . '/' . $action;
        $params['Variant'] = ['product_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => '/shop/crud/variant'
],
        'id',
        'title',
        'thumbnail_image',
        'is_online',
        'rank',
        'price',
        'hex_color',
        'stock',
        'sku',
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
    'content' => $this->blocks['eluhr\shop\models\Product'],
    'active'  => true,
],
[
    'content' => $this->blocks['FirstVariant'],
    'label'   => '<small>First Variant <span class="badge badge-default">'. $model->getFirstVariant()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['ActiveVariants'],
    'label'   => '<small>Active Variants <span class="badge badge-default">'. $model->getActiveVariants()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Tags'],
    'label'   => '<small>Tags <span class="badge badge-default">'. $model->getTags()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Variants'],
    'label'   => '<small>Variants <span class="badge badge-default">'. $model->getVariants()->count() . '</span></small>',
    'active'  => false,
],
 ]
                 ]
);
    ?>
</div>
