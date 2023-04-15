<?php
/**
 * @var Filter $model
 */

use kartik\select2\Select2;
use eluhr\shop\models\Filter;
use eluhr\shop\models\Tag;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="row">
    <div class="col-xs-12">
        <div class="form-group">
            <?= Html::a(Yii::t('shop', '{icon} Back', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['dashboard/tags/index'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <h2><?= $model->isNewRecord ? Yii::t('shop', 'New Tag') : $model->name ?></h2>
        <?php
        $form = ActiveForm::begin();

        echo $form->field($model, 'name');

        echo Html::errorSummary($model);

        echo Html::submitButton(Yii::t('shop', '{icon} Save', ['icon' => FA::icon(FA::_SAVE)]), ['class' => 'btn btn-primary']);
        ActiveForm::end();
        ?>
    </div>
</div>
