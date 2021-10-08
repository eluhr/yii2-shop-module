<?php
/**
 * @var Variant $model
 */

use dosamigos\ckeditor\CKEditor;
use hrzg\filemanager\widgets\FileManagerInputWidget;
use kartik\color\ColorInput;
use kartik\number\NumberControl;
use eluhr\shop\models\ShopSettings;
use eluhr\shop\models\Variant;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="row">
    <div class="col-xs-12">
        <div class="form-group">
            <div class="btn-toolbar pull-left">
                <?= Html::a(Yii::t('shop', '{icon} Back to product', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['product-edit', 'id' => $model->product_id], ['class' => 'btn btn-default']) ?>
            </div>
            <?php if (!$model->isNewRecord): ?>
                <div class="btn-toolbar pull-right">
                    <?= Html::a(FA::icon(FA::_TRASH_O), ['variant-delete', 'id' => $model->id], ['class' => 'btn btn-danger', 'data-confirm' => Yii::t('shop', 'Are you sure you want to delete this?')]) ?>
                </div>
            <?php endif; ?>
            <span class="clearfix"></span>
        </div>
    </div>
    <div class="col-xs-12">
        <h2><?= $model->isNewRecord ? Yii::t('shop', 'New Variant') : $model->title ?></h2>
        <?php
        $form = ActiveForm::begin();

        echo $form->field($model, 'title');
        echo $form->field($model, 'sku');
        echo $form->field($model, 'description')->widget(CKEditor::class);
        echo $form->field($model, 'thumbnail_image')->widget(FileManagerInputWidget::class, ['handlerUrl' => '/filefly/api']);
        echo $form->field($model, 'price')->widget(NumberControl::class, ['maskedInputOptions' => [
            'suffix' => ' ' . $this->context->module->currency,
            'groupSeparator' => '.',
            'radixPoint' => ',',
            'rightAlign' => false
        ]]);
        echo $form->field($model, 'is_online')->checkbox([], false);
        echo $form->field($model, 'rank');
        echo $form->field($model, 'hex_color')->widget(ColorInput::class);
        $stockField = $form->field($model, 'stock')->input('number');
        if (!$model->isNewRecord && $model->fewAvailable()) {
            $stockField->hint(Yii::t('shop', 'There are less than {count} in stock', ['count' => ShopSettings::shopProductFewAvailableWarning()]), ['class' => 'text-warning']);
        }
        echo $stockField;

        echo Html::errorSummary($model);

        echo Html::submitButton(Yii::t('shop', '{icon} Save', ['icon' => FA::icon(FA::_SAVE)]), ['class' => 'btn btn-primary']);
        ActiveForm::end();
        ?>
    </div>
</div>
