<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
    * @var eluhr\shop\models\search\Order $searchModel
*/

$this->title = Yii::t('shop', 'Order');
$this->params['breadcrumbs'][] = $this->title;

if (isset($actionColumnTemplates)) {
$actionColumnTemplate = implode(' ', $actionColumnTemplates);
    $actionColumnTemplateString = $actionColumnTemplate;
} else {
Yii::$app->view->params['pageButtons'] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('shop', 'New'), ['create'], ['class' => 'btn btn-success']);
    $actionColumnTemplateString = "{view} {update} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">'.$actionColumnTemplateString.'</div>';
?>
<div class="giiant-crud order-index">

    <?php
//             echo $this->render('_search', ['model' =>$searchModel]);
        ?>

    
    <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

    <h1>
        <?= Yii::t('shop.plural', 'Order') ?>
        <small>
            <?= Yii::t('shop', 'List') ?>
        </small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('shop', 'New'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">

                                                                                                                                        
            <?= 
            \yii\bootstrap\ButtonDropdown::widget(
            [
            'id' => 'giiant-relations',
            'encodeLabel' => false,
            'label' => '<span class="glyphicon glyphicon-paperclip"></span> ' . Yii::t('shop', 'Relations'),
            'dropdown' => [
            'options' => [
            'class' => 'dropdown-menu-right'
            ],
            'encodeLabels' => false,
            'items' => [
            [
                'url' => ['/shop/crud/discount-code/index'],
                'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('shop', 'Discount Code'),
            ],
                                [
                'url' => ['/shop/crud/user/index'],
                'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('shop', 'User'),
            ],
                                [
                'url' => ['/shop/crud/order-item/index'],
                'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('shop', 'Order Item'),
            ],
                                [
                'url' => ['/shop/crud/variant/index'],
                'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('shop', 'Variant'),
            ],
                    
]
            ],
            'options' => [
            'class' => 'btn-default'
            ]
            ]
            );
            ?>
        </div>
    </div>

    <hr />

    <div class="table-responsive">
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
        'class' => yii\widgets\LinkPager::className(),
        'firstPageLabel' => Yii::t('shop', 'First'),
        'lastPageLabel' => Yii::t('shop', 'Last'),
        ],
                    'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
        'headerRowOptions' => ['class'=>'x'],
        'columns' => [
                [
            'class' => 'yii\grid\ActionColumn',
            'template' => $actionColumnTemplateString,
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('shop', 'View'),
                        'aria-label' => Yii::t('shop', 'View'),
                        'data-pjax' => '0',
                    ];
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                }
            ],
            'urlCreator' => function($action, $model, $key, $index) {
                // using the column name as key, not mapping to 'id' like the standard generator
                $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                return Url::toRoute($params);
            },
            'contentOptions' => ['nowrap'=>'nowrap']
        ],
			'id',
			'first_name',
			'surname',
			'email:email',
			'street_name',
			'house_number',
			'postal',
			/*'city',*/
			/*[
			                'attribute'=>'type',
			                'value' => function ($model) {
			                    return eluhr\shop\models\Order::getTypeValueLabel($model->type);
			                }    
			            ],*/
			/*'is_executed',*/
			/*// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
			[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'discount_code_id',
			    'value' => function ($model) {
			        if ($rel = $model->discountCode) {
			            return Html::a($rel->label, ['/shop/crud/discount-code/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			        } else {
			            return '';
			        }
			    },
			    'format' => 'raw',
			],*/
			/*'info_mail_has_been_sent',*/
			/*'has_different_delivery_address',*/
			/*'paid',*/
			/*'date_of_birth',*/
			/*'created_at',*/
			/*'updated_at',*/
			/*'payment_details:ntext',*/
			/*// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
			[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'user_id',
			    'value' => function ($model) {
			        if ($rel = $model->user) {
			            return Html::a($rel->id, ['/shop/crud/user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			        } else {
			            return '';
			        }
			    },
			    'format' => 'raw',
			],*/
			/*'internal_notes:ntext',*/
			/*'customer_details:ntext',*/
			/*[
			                'attribute'=>'status',
			                'value' => function ($model) {
			                    return eluhr\shop\models\Order::getStatusValueLabel($model->status);
			                }    
			            ],*/
			/*'shipping_price',*/
			/*'delivery_first_name',*/
			/*'delivery_surname',*/
			/*'delivery_street_name',*/
			/*'delivery_house_number',*/
			/*'delivery_postal',*/
			/*'delivery_city',*/
			/*'shipment_link',*/
			/*'invoice_number',*/
                ]
        ]); ?>
    </div>

</div>


<?php \yii\widgets\Pjax::end() ?>


