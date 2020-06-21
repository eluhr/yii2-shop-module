<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\Filter $model
*/

$this->title = Yii::t('shop', 'Filter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Filter'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('shop', 'Edit');
?>
<div class="giiant-crud filter-update">

    <h1>
        <?= Yii::t('shop', 'Filter') ?>
        <small>
                        <?= Html::encode($model->name) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('shop', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
