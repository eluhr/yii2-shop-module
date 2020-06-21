<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\Product $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="product-form">

    <?php $form = ActiveForm::begin(
    [
    'id' => 'Product',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-danger',
    'fieldConfig' => [
             'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
             'horizontalCssClasses' => [
                 'label' => 'col-sm-2',
                 #'offset' => 'col-sm-offset-4',
                 'wrapper' => 'col-sm-8',
                 'error' => '',
                 'hint' => '',
             ],
         ],
    ]
);
    ?>

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>
            

<!-- attribute filterIds -->
        </p>
        <?php $this->endBlock(); ?>
        
        <?=
    Tabs::widget(
        [
                    'encodeLabels' => false,
                    'items' => [
                        [
    'label'   => Yii::t('shop', 'Product'),
    'content' => $this->blocks['main'],
    'active'  => true,
],
                    ]
                 ]
    );
    ?>
        <hr/>

        <?php echo $form->errorSummary($model); ?>

        <?= Html::submitButton(
        '<span class="glyphicon glyphicon-check"></span> ' .
        ($model->isNewRecord ? Yii::t('shop', 'Create') : Yii::t('shop', 'Save')),
        [
        'id' => 'save-' . $model->formName(),
        'class' => 'btn btn-success'
        ]
    );
        ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>

