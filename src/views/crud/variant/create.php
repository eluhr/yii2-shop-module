<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\Variant $model
*/

$this->title = Yii::t('shop', 'Variant');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Variants'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud variant-create">

    <h1>
        <?= Yii::t('shop', 'Variant') ?>
        <small>
                        <?= Html::encode($model->label) ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?=             Html::a(
    Yii::t('shop', 'Cancel'),
    \yii\helpers\Url::previous(),
    ['class' => 'btn btn-default']
) ?>
        </div>
    </div>

    <hr />

    <?= $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
