<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\search\Variant $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="variant-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'product_id') ?>

		<?= $form->field($model, 'title') ?>

		<?= $form->field($model, 'thumbnail_image') ?>

		<?= $form->field($model, 'is_online') ?>

		<?php // echo $form->field($model, 'rank') ?>

		<?php // echo $form->field($model, 'price') ?>

		<?php // echo $form->field($model, 'hex_color') ?>

		<?php // echo $form->field($model, 'stock') ?>

		<?php // echo $form->field($model, 'sku') ?>

		<?php // echo $form->field($model, 'description') ?>

		<?php // echo $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('shop', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('shop', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
