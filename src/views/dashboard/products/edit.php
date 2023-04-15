<?php
/**
 * @var Product $model
 */

use dosamigos\ckeditor\CKEditor;
use eluhr\shop\models\ShopSettings;
use kartik\number\NumberControl;
use kartik\select2\Select2;
use eluhr\shop\models\Product;
use eluhr\shop\models\Tag;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use hrzg\filemanager\widgets\FileManagerInputWidget;

?>

<div class="row">
    <div class="col-xs-12">
        <div class="form-group pull-left">
            <?= Html::a(Yii::t('shop', '{icon} Back', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['dashboard/products/index'], ['class' => 'btn btn-default']); ?>
        </div>
        <?php if (!$model->getIsNewRecord()): ?>
        <div class="form-group pull-right">
            <?= Html::a(Yii::t('shop', '{icon} Show in frontend', ['icon' => FA::icon(FA::_EYE)]),$model->detailUrl(), ['class' => 'btn btn-primary','target' => '_blank']); ?>
        </div>
        <?php endif; ?>
        <span class="clearfix"></span>
    </div>
    <div class="col-xs-12 col-md-8">
        <h2><?= $model->isNewRecord ? Yii::t('shop', 'New Product') : $model->title ?></h2>
        <?php
        $form = ActiveForm::begin();

        echo $form->field($model, 'title');
        echo $form->field($model, 'is_online')->checkbox([], false);
        echo $form->field($model, 'hide_in_overview')->checkbox([], false);
        echo $form->field($model, 'is_inventory_independent')->checkbox([], false);
        echo $form->field($model, 'rank');
        if (ShopSettings::shopProductShowShippingCosts() || $model->shipping_price > 0) {
            echo $form->field($model, 'staggering_shipping_cost')->checkbox([], false);
            echo $form->field($model, 'shipping_price')->widget(NumberControl::class, ['maskedInputOptions' => [
                'suffix' => ' ' . $this->context->module->currency,
                'groupSeparator' => '.',
                'radixPoint' => ',',
                'rightAlign' => false
            ]]);
        }
        echo $form->field($model, 'description')->widget(CKEditor::class);
        echo $form->field($model, 'filterIds')->widget(Select2::class, [
            'data' => ArrayHelper::map(Tag::find()->all(), 'id', 'name'),
            'options' => [
                'multiple' => true
            ]
        ]);

        echo Html::errorSummary($model);

        echo Html::submitButton(Yii::t('shop', '{icon} Save', ['icon' => FA::icon(FA::_SAVE)]), ['class' => 'btn btn-primary']);
        ActiveForm::end();
        ?>
    </div>
    <div class="col-xs-12 col-md-4">
        <h3><?= Yii::t('shop', 'Product variants') ?></h3>
        <div class="list-group">
            <?php
            foreach ($model->variants as $variant) {
                $content = Html::tag('span', ($variant->fewAvailable() ? FA::icon(FA::_EXCLAMATION, ['class' => 'text-warning']) . ' ' : '') . $variant->title, ['class' => 'pull-left']) . Html::tag('span', FA::icon(FA::_CIRCLE, ['class' => 'text-' . ($variant->is_online ? 'success' : 'danger')]), ['class' => 'pull-right']) . Html::tag('span', '', ['class' => 'clearfix']);
                echo Html::a($content, ['dashboard/variants/edit', 'id' => $variant->id], ['class' => 'list-group-item']);
            }
            if ($model->isNewRecord) {
                echo Html::tag('div', Yii::t('shop', '{icon} New Variant', ['icon' => FA::icon(FA::_PLUS)]), ['class' => 'list-group-item disabled active', 'title' => Yii::t('shop', 'Please create a new product before adding a new variant')]);
            } else {
                echo Html::a(Yii::t('shop', '{icon} New Variant', ['icon' => FA::icon(FA::_PLUS)]), ['dashboard/variants/edit', 'product_id' => $model->id], ['class' => 'list-group-item active']);
            }
            ?>
        </div>
    </div>
</div>
