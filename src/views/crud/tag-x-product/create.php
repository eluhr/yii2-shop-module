<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\TagXProduct $model
*/

$this->title = Yii::t('shop', 'Tag X Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Tag X Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud tag-x-product-create">

    <h1>
                <?= Html::encode($model->tag_id) ?>
        <small>
            <?= Yii::t('shop', 'Tag X Product') ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?=             Html::a(
            Yii::t('shop', 'Cancel'),
            \yii\helpers\Url::previous(),
            ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?= $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
