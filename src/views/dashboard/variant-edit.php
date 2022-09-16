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
use kartik\select2\Select2;
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
                    <?= Html::a(FA::icon(FA::_COPY), ['variant-copy', 'id' => $model->id], ['class' => 'btn btn-warning', 'data' => [
                            'confirm' => Yii::t('shop', 'Are you sure you want to copy this?'),
                        'method' => 'post'
                    ]]) ?>
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
        ?>
        <div class="row">
            <div class="col-xs-12 col-md-3">
                <?php echo $form->field($model, 'min_days_shipping_duration')->input('number',['min' => 1]); ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <?php echo $form->field($model, 'max_days_shipping_duration')->input('number',['min' => 1]); ?>
            </div>
        </div>
        <?php
        if ($model->getIsNewRecord()) {
            $model->setAttribute('description', ShopSettings::shopProductVariantTextTemplate());
        }
        echo $form->field($model, 'description')->widget(CKEditor::class);
        echo $form->field($model, 'thumbnail_image')->widget(FileManagerInputWidget::class, [
            'handlerUrl' => '/filefly/api',
            'select2Options' => [
                'theme' => Select2::THEME_BOOTSTRAP
            ]
        ]);
        echo $form->field($model, 'configurator_bg_image')->widget(FileManagerInputWidget::class, [
            'handlerUrl' => '/filefly/api',
            'select2Options' => [
                'theme' => Select2::THEME_BOOTSTRAP
            ]
        ]);
        echo $form->field($model, 'configurator_url');
        echo $form->field($model, 'price')->widget(NumberControl::class, ['maskedInputOptions' => [
            'suffix' => ' ' . $this->context->module->currency,
            'groupSeparator' => '.',
            'radixPoint' => ',',
            'rightAlign' => false
        ]]);
        echo $form->field($model, 'discount_price')->widget(NumberControl::class, ['maskedInputOptions' => [
            'suffix' => ' ' . $this->context->module->currency,
            'groupSeparator' => '.',
            'radixPoint' => ',',
            'rightAlign' => false
        ]]);
        if (ShopSettings::shopProductShowVat())
            if ($model->getIsNewRecord()) {
                $model->vat = ShopSettings::shopProductDefaultVat();
                $model->include_vat = 1;
            }
        echo $form->field($model, 'include_vat')->checkbox([], false);
        echo $form->field($model, 'vat')->widget(NumberControl::class, ['maskedInputOptions' => [
            'suffix' => ' ' . '%',
            'groupSeparator' => '.',
            'radixPoint' => ',',
            'rightAlign' => false
        ]]);
        echo $form->field($model, 'extra_info')->textInput(['placeholder' => Yii::t('shop', 'Size S;Size M;Size L')]);
        echo $form->field($model, 'is_online')->checkbox([], false);
        echo $form->field($model, 'rank');
        echo $form->field($model, 'hex_color')->widget(ColorInput::class);
        echo $form->field($model, 'show_affiliate_link')->checkbox([], false);
        echo $form->field($model, 'affiliate_link_url');
        if (!$model->product->is_inventory_independent) {
            $stockField = $form->field($model, 'stock')->input('number');
            if (!$model->isNewRecord && $model->fewAvailable()) {
                $stockField->hint(Yii::t('shop', 'There are less than {count} in stock', ['count' => ShopSettings::shopProductFewAvailableWarning()]), ['class' => 'text-warning']);
            }
            echo $stockField;
        }
        if (ShopSettings::shopProductAllowConfigurableVariant()) {
            echo $form->field($model, 'configurator_url');
        }
        echo Html::errorSummary($model);

        echo Html::submitButton(Yii::t('shop', '{icon} Save', ['icon' => FA::icon(FA::_SAVE)]), ['class' => 'btn btn-primary']);
        ActiveForm::end();
        ?>
    </div>
</div>
