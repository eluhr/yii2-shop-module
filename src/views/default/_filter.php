<?php

use eluhr\shop\models\ShopSettings;
use kartik\select2\Select2;
use eluhr\shop\models\Filter;
use eluhr\shop\models\form\Filter as FilterForm;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $filters Filter[]
 * @var $filterForm FilterForm
 */
?>
<div class="filters">
    <?php

        $form = ActiveForm::begin(['method' => 'get', 'action' => ['']]);

        if (ShopSettings::shopGeneralShowSearch()) {
            echo $form->field($filterForm, 'q');
        }

        foreach ($filters as $filter) {
            $data = $filter->tagData();
            if (!empty($data)) {
                $field = $form->field($filterForm, 'tag[' . $filter->id . '][]', ['options' => ['class' => 'filter']]);
                if ($filter->presentation === Filter::PRESENTATION_DROPDOWN) {
                    $field->widget(Select2::class, [
                        'data' => $data,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'hideSearch' => true,
                        'pluginOptions' => [
                            'placeholder' => Yii::t('shop', 'Please choose'),
                            'allowClear' => true,
                        ],
                        'options' => [
                            'data-filter' => 'filter-form',
                            'class' => 'filter-collapse'
                        ]
                    ]);
                } else {
                    $field->radioList($data, [
                        'itemOptions' => [
                            'data-filter' => 'filter-form'
                        ],
                        'class' => 'filter-collapse'
                    ]);
                }
                echo $field->label($filter->name);
            }
        }
        if ($filterForm->isFiltered) {
            echo Html::a(Yii::t('shop', '{icon} Reset', ['icon' => FA::icon(FA::_UNDO)]), [''], ['class' => 'btn btn-primary']);
        }
        ActiveForm::end();
    ?>
</div>
