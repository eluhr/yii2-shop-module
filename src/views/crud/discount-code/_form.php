<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\DiscountCode $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="discount-code-form">

    <?php $form = ActiveForm::begin([
    'id' => 'DiscountCode',
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
            

<!-- attribute type -->
			<?= $form->field($model, 'type')->textInput() ?>

<!-- attribute used -->
			<?= $form->field($model, 'used')->textInput() ?>

<!-- attribute code -->
			<?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

<!-- attribute value -->
			<?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

<!-- attribute expiration_date -->
			<?= $form->field($model, 'expiration_date')->textInput() ?>

<!-- attribute created_at -->

<!-- attribute updated_at -->
        </p>
        <?php $this->endBlock(); ?>
        
        <?=
    Tabs::widget(
                 [
                    'encodeLabels' => false,
                    'items' => [ 
                        [
    'label'   => Yii::t('shop', 'DiscountCode'),
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

