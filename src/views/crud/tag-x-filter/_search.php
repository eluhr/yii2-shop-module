<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\search\TagXFilter $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="tag-x-filter-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'tag_id') ?>

		<?= $form->field($model, 'facet_id') ?>

		<?= $form->field($model, 'show_in_frontend') ?>

		<?= $form->field($model, 'rank') ?>

		<?= $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'updated_at')?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('shop', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('shop', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
