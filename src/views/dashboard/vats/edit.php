<?php
/**
 * @var Filter $model
 */

use eluhr\shop\models\Filter;
use eluhr\shop\models\Vat;
use kartik\number\NumberControl;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="row">
    <div class="col-xs-12">
        <div class="form-group">
            <?= Html::a(Yii::t('shop', '{icon} Back', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['dashboard/vats/index'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <h2><?= $model->isNewRecord ? Yii::t('shop', 'New Vat') : $model->value ?></h2>
        <?php
        $form = ActiveForm::begin();

        echo $form->field($model, 'value')->widget(NumberControl::class, ['maskedInputOptions' => [
            'suffix' => ' ' . '%',
            'groupSeparator' => '.',
            'radixPoint' => ',',
            'rightAlign' => false
        ]]);
        echo $form->field($model, 'desc');

        echo Html::errorSummary($model);

        echo Html::submitButton(Yii::t('shop', '{icon} Save', ['icon' => FA::icon(FA::_SAVE)]), ['class' => 'btn btn-primary']);
        ActiveForm::end();
        ?>
    </div>
</div>
