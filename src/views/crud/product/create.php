<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\Product $model
*/

$this->title = Yii::t('shop', 'Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud product-create">

    <h1>
        <?= Yii::t('shop', 'Product') ?>
        <small>
                        <?= Html::encode($model->title) ?>
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
