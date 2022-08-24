<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\search\Order $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'paypal_id') ?>

		<?= $form->field($model, 'paypal_token') ?>

		<?= $form->field($model, 'paypal_payer_id') ?>

		<?= $form->field($model, 'is_executed') ?>

		<?php // echo $form->field($model, 'date_of_birth') ?>

		<?php // echo $form->field($model, 'internal_notes') ?>

		<?php // echo $form->field($model, 'customer_details') ?>

		<?php // echo $form->field($model, 'discount_code_id') ?>

		<?php // echo $form->field($model, 'info_mail_has_been_sent') ?>

		<?php // echo $form->field($model, 'first_name') ?>

		<?php // echo $form->field($model, 'surname') ?>

		<?php // echo $form->field($model, 'email') ?>

		<?php // echo $form->field($model, 'street_name') ?>

		<?php // echo $form->field($model, 'house_number') ?>

		<?php // echo $form->field($model, 'postal') ?>

		<?php // echo $form->field($model, 'city') ?>

		<?php // echo $form->field($model, 'has_different_delivery_address') ?>

		<?php // echo $form->field($model, 'delivery_first_name') ?>

		<?php // echo $form->field($model, 'delivery_surname') ?>

		<?php // echo $form->field($model, 'delivery_street_name') ?>

		<?php // echo $form->field($model, 'delivery_house_number') ?>

		<?php // echo $form->field($model, 'delivery_postal') ?>

		<?php // echo $form->field($model, 'delivery_city') ?>

		<?php // echo $form->field($model, 'status') ?>

		<?php // echo $form->field($model, 'shipment_link') ?>

		<?php // echo $form->field($model, 'paid') ?>

		<?php // echo $form->field($model, 'shipping_price') ?>

		<?php // echo $form->field($model, 'type') ?>

		<?php // echo $form->field($model, 'invoice_number') ?>

		<?php // echo $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('shop', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('shop', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
