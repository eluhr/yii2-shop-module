<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\TagXFilter $model
*/

$this->title = Yii::t('shop', 'Tag X Filter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Tag X Filters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud tag-x-filter-create">

    <h1>
        <?= Yii::t('shop', 'Tag X Filter') ?>
        <small>
                        <?= Html::encode($model->tag_id) ?>
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
