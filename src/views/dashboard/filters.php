<?php

use eluhr\shop\models\Filter;
use rmrevin\yii\fontawesome\FA;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
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
            <?= Html::a(Yii::t('shop', '{icon} New', ['icon' => FA::icon(FA::_PLUS)]), ['filter-edit'], ['class' => 'btn btn-success']) ?>
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
                'delete' => function ($url) {
                    return Html::a(FA::icon(FA::_TRASH_O), $url, ['class' => 'btn btn-danger', 'data-confirm' => Yii::t('shop', 'Are you sure you want to delete this?')]);
                },
            ],
            'urlCreator' => function (string $action, Filter $model) {
                return Url::to(['filter-' . $action, 'id' => $model->id]);
            }
        ],
        'name',
        'rank',
        'presentation',
        [
            'attribute' => 'tagsFilter',
            'label' => \Yii::t('shop', 'Tags'),
            'value' => function (Filter $model) {
                $labels = [];
                foreach ($model->tags as $tag) {
                    $labels[] = Html::a($tag->name, ['tag-edit', 'id' => $tag->id], ['class' => 'label label-primary']);
                }
                return implode(' ', $labels);
            },
            'format' => 'html'
        ]
    ]
]);
