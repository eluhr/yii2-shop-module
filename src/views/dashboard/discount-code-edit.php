<?php
/**
 * @var DiscountCode $model
 */

use hrzg\filemanager\widgets\FileManagerInputWidget;
use eluhr\shop\models\DiscountCode;
use eluhr\shop\models\Variant;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use zhuravljov\yii\widgets\DatePicker;

?>

<div class="row">
    <div class="col-xs-12">
        <div class="form-group">
            <div class="btn-toolbar pull-left">
                <?= Html::a(Yii::t('shop', '{icon} Back', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['discount-codes'], ['class' => 'btn btn-default']) ?>
            </div>
            <?php if (!$model->isNewRecord): ?>
                <div class="btn-toolbar pull-right">
                    <?= Html::a(FA::icon(FA::_TRASH_O), ['discount-code-delete', 'id' => $model->id], ['class' => 'btn btn-danger', 'data-confirm' => Yii::t('shop', 'Are you sure you want to delete this?')]) ?>
                </div>
            <?php endif; ?>
            <span class="clearfix"></span>
        </div>
    </div>
    <div class="col-xs-12">
        <h2><?= $model->isNewRecord ? Yii::t('shop', 'New Discount code') : $model->code ?></h2>
        <?php
        $form = ActiveForm::begin();

        echo $form->field($model, 'code');
        echo $form->field($model, 'type')->radioList(DiscountCode::optsTypes());
        echo $form->field($model, 'value')->input('number', ['max' => 100, 'min' => 0.01, 'step' => 0.01]);
        if (!$model->isNewRecord) {
            $model->expiration_date = date('d.m.Y', strtotime($model->expiration_date));
        }
        echo $form->field($model, 'expiration_date')->widget(DatePicker::class, [
            'clientOptions' => [
                'startDate' => date('d.m.Y'),
                'language' => 'de',
                'autoclose' => true,
                'todayHighlight' => true,
            ]
        ]);

        echo Html::errorSummary($model);

        echo Html::submitButton(Yii::t('shop', '{icon} Save', ['icon' => FA::icon(FA::_SAVE)]), ['class' => 'btn btn-primary']);
        ActiveForm::end();
        ?>
    </div>
</div>
