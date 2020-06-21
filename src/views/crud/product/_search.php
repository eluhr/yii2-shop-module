<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\search\Product $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'title') ?>

		<?= $form->field($model, 'is_online') ?>

		<?= $form->field($model, 'rank') ?>

		<?= $form->field($model, 'shipping_price') ?>

		<?php // echo $form->field($model, 'staggering_shipping_cost')?>

		<?php // echo $form->field($model, 'description')?>

		<?php // echo $form->field($model, 'popularity')?>

		<?php // echo $form->field($model, 'created_at')?>

		<?php // echo $form->field($model, 'updated_at')?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('shop', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('shop', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
