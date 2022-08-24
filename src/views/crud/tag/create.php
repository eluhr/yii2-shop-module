<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\Tag $model
*/

$this->title = Yii::t('shop', 'Tag');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud tag-create">

    <h1>
                <?= Html::encode($model->name) ?>
        <small>
            <?= Yii::t('shop', 'Tag') ?>
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
