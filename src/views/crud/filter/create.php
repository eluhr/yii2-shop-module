<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\Filter $model
*/

$this->title = Yii::t('shop', 'Filter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Filters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud filter-create">

    <h1>
        <?= Yii::t('shop', 'Filter') ?>
        <small>
                        <?= Html::encode($model->name) ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?=             Html::a(
    Yii::t('shop', 'Cancel'),
    \yii\helpers\Url::previous(),
    ['class' => 'btn btn-default']
) ?>
        </div>
    </div>

    <hr />

    <?= $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
