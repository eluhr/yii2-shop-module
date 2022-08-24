<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var eluhr\shop\models\TagXProduct $model
*/

$this->title = Yii::t('shop', 'Tag X Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Tag X Product'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->tag_id, 'url' => ['view', 'tag_id' => $model->tag_id, 'product_id' => $model->product_id]];
$this->params['breadcrumbs'][] = Yii::t('shop', 'Edit');
?>
<div class="giiant-crud tag-x-product-update">

    <h1>
                <?= Html::encode($model->tag_id) ?>

        <small>
            <?= Yii::t('shop', 'Tag X Product') ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('shop', 'View'), ['view', 'tag_id' => $model->tag_id, 'product_id' => $model->product_id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
