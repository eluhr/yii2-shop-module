<?php

use eluhr\shop\models\DiscountCode;
use eluhr\shop\models\Product;
use rmrevin\yii\fontawesome\FA;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Model $filterModel
 * @var View $this
 */

?>

    <div class="form-group">
        <div class="btn-toolbar">
            <?= Html::a(Yii::t('shop', '{icon} Back', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['index'], ['class' => 'btn btn-default']) ?>
            <?= Html::a(Yii::t('shop', '{icon} New', ['icon' => FA::icon(FA::_PLUS)]), ['discount-code-edit'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $filterModel,
    'columns' => [
        [
            'class' => ActionColumn::class,
            'template' => '{edit} {delete}',
            'buttons' => [
                'edit' => function ($url) {
                    return Html::a(FA::icon(FA::_PENCIL), $url, ['class' => 'btn btn-primary']);
                },
                'delete' => function ($url) {
                    return Html::a(FA::icon(FA::_TRASH_O), $url, ['class' => 'btn btn-danger', 'data-confirm' => Yii::t('shop', 'Are you sure you want to delete this?')]);
                },
            ],
            'urlCreator' => function (string $action, DiscountCode $model) {
                return Url::to(['discount-code-' . $action, 'id' => $model->id]);
            }
        ],
        'code',
        [
            'attribute' => 'type',
            'filter' => DiscountCode::optsTypes(),
            'value' => function (DiscountCode $model) {
                return DiscountCode::optsTypes()[$model->type] ?? Yii::t('shop','Undefined');
            }
        ],
        [
            'attribute' => 'value',
            'value' => function (DiscountCode $model) {
                return $model->prettyValue();
            }
        ],
        'used',
        [
            'attribute' => 'expiration_date',
            'value' => function (DiscountCode $model) {
                return date('d.m.Y', strtotime($model->expiration_date)) . ' ' . FA::icon(FA::_CIRCLE, ['class' => 'text-' . ($model->isExpired() ? 'danger' : 'success')]);
            },
            'format' => 'html'
        ]
    ]
]);
