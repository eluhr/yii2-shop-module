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
            <?= Html::a(Yii::t('shop', '{icon} Back', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['filters'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>
    <div class="col-xs-12 col-md-8">
        <h2><?= $model->isNewRecord ? Yii::t('shop', 'New Filter') : $model->name ?></h2>
        <?php
        $form = ActiveForm::begin();

        echo $form->field($model, 'name');
        if ($model->isNewRecord) {
            $model->presentation = $model::PRESENTATION_DROPDOWN;
        }
        echo $form->field($model, 'presentation')->radioList(Filter::presentations());
        echo $form->field($model, 'is_online')->checkbox([], false);
        echo $form->field($model, 'rank');
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
        <h3><?= Yii::t('shop', 'Tags') ?></h3>
        <div class="list-group">
            <?php
            foreach ($model->tags as $tag) {
                echo Html::a($tag->name, ['tag-edit', 'id' => $tag->id], ['class' => 'list-group-item']);
            }
            if ($model->isNewRecord) {
                echo Html::tag('div', Yii::t('shop', '{icon} New Tag', ['icon' => FA::icon(FA::_PLUS)]), ['class' => 'list-group-item disabled active', 'title' => Yii::t('shop', 'Please create a new filter before adding a new tag')]);
            } else {
                echo Html::a(Yii::t('shop', '{icon} New Tag', ['icon' => FA::icon(FA::_PLUS)]), ['tag-edit'], ['class' => 'list-group-item active']);
            }
            ?>
        </div>
    </div>
</div>
