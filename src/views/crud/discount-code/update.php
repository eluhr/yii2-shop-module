<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\DiscountCode $model
*/

$this->title = Yii::t('shop', 'Discount Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Discount Code'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('shop', 'Edit');
?>
<div class="giiant-crud discount-code-update">

    <h1>
        <?= Yii::t('shop', 'Discount Code') ?>
        <small>
                        <?= Html::encode($model->label) ?>
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
