<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\search\OrderItem $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="order-item-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'order_id') ?>

		<?= $form->field($model, 'variant_id') ?>

		<?= $form->field($model, 'configuration_id') ?>

		<?= $form->field($model, 'name') ?>

		<?= $form->field($model, 'quantity') ?>

		<?php // echo $form->field($model, 'extra_info') ?>

		<?php // echo $form->field($model, 'single_price') ?>

		<?php // echo $form->field($model, 'single_net_price') ?>

		<?php // echo $form->field($model, 'vat') ?>

		<?php // echo $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'updated_at') ?>

		<?php // echo $form->field($model, 'configuration_json') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('shop', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('shop', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
