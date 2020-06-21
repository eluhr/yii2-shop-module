<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\Setting $model
*/

$this->title = Yii::t('shop', 'Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Setting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->key, 'url' => ['view', 'key' => $model->key]];
$this->params['breadcrumbs'][] = Yii::t('shop', 'Edit');
?>
<div class="giiant-crud setting-update">

    <h1>
        <?= Yii::t('shop', 'Setting') ?>
        <small>
                        <?= Html::encode($model->key) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('shop', 'View'), ['view', 'key' => $model->key], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
