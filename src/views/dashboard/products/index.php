<?php

use eluhr\shop\models\Product;
use rmrevin\yii\fontawesome\FA;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Model $filterModel
 * @var View $this
 */

$this->registerJs(
    <<<JS
$("[date-toggle='status']").on("change", function() {
  this.form.submit();
})
JS
);

?>

    <div class="form-group">
        <div class="btn-toolbar">
            <?= Html::a(Yii::t('shop', '{icon} Back', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['dashboard/index'], ['class' => 'btn btn-default']) ?>
            <?= Html::a(Yii::t('shop', '{icon} New', ['icon' => FA::icon(FA::_PLUS)]), ['edit'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $filterModel,
    'columns' => [
        [
            'class' => ActionColumn::class,
            'template' => '{edit} {status} {delete}',
            'buttons' => [
                'edit' => function ($url) {
                    return Html::a(FA::icon(FA::_PENCIL), $url, ['class' => 'btn btn-primary']);
                },
                'status' => function ($url, Product $model) {
                    $html = [];
                    $html[] = Html::beginForm($url, 'post', ['style' => 'display: inline-block;']);
                    $html[] = Html::activeCheckbox($model, 'is_online', ['date-toggle' => 'status', 'label' => false]);
                    $html[] = Html::endForm();
                    return implode(PHP_EOL, $html);
                },
                'delete' => function ($url) {
                    return Html::a(FA::icon(FA::_TRASH_O), $url, ['class' => 'btn btn-danger', 'data' => [
                        'confirm' => Yii::t('shop', 'Are you sure you want to delete this?'),
                        'method' => 'post'
                    ]]);
                },
            ]
        ],
        'title',
        [
            'label' => Yii::t('shop', 'Variants'),
            'value' => function (Product $model) {
                $html = [];
                foreach ($model->variants as $variant) {
                    $html[] = Html::tag('p', ($variant->fewAvailable() ? FA::icon(FA::_EXCLAMATION, ['class' => 'text-warning']) . ' ' : '') . $variant->title . ' ' . FA::icon(FA::_CIRCLE, ['class' => 'text-' . ($variant->is_online ? 'success' : 'danger')]) . ' ' . Html::a(FA::icon(FA::_PENCIL), ['dashboard/variants/edit', 'id' => $variant->id], ['class' => 'btn btn-xs btn-primary']));
                }
                $html[] = Html::a(Yii::t('shop', '{icon} Add Variant', ['icon' => FA::icon(FA::_PLUS)]), ['dashboard/variants/edit', 'product_id' => $model->id], ['class' => 'btn btn-xs btn-success']);
                return implode(PHP_EOL, $html);
            },
            'format' => 'html'
        ],
        [
            'attribute' => 'tagsFilter',
            'label' => \Yii::t('shop', 'Tags'),
            'value' => function (Product $model) {
                $labels = [];
                foreach ($model->tags as $tag) {
                    $labels[] = Html::a($tag->name, ['dashboard/tags/edit', 'id' => $tag->id], ['class' => 'label label-primary']);
                }
                return implode(' ', $labels);
            },
            'format' => 'html'
        ],
        [
            'label' => Yii::t('shop', 'Online'),
            'attribute' => 'is_online',
            'filter' => [
                0 => Yii::t('shop', 'Deactivated'),
                1 => Yii::t('shop', 'Online'),
            ],
            'value' => function (Product $model) {
                $dot = FA::icon(FA::_CIRCLE, ['class' => 'text-' . ($model->is_online ? 'success' : 'danger')]);
                $info = '';
                if ($model->hide_in_overview) {
                    $info = ' ' . Html::tag('small', Yii::t('shop', 'Online but not visible in overview'));
                }
                return $dot . $info;
            },
            'format' => 'html'
        ]
    ]
]);
