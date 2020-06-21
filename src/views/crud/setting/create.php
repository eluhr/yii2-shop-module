<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\Setting $model
*/

$this->title = Yii::t('shop', 'Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud setting-create">

    <h1>
        <?= Yii::t('shop', 'Setting') ?>
        <small>
                        <?= Html::encode($model->key) ?>
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
