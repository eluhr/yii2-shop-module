<?php

use eluhr\shop\models\Product;
use eluhr\shop\models\Variant;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\View;

/**
 * @var Product $product
 * @var View $this
 */

$id = 'a-' . Inflector::slug($product->title) . '-' . $product->id;

$this->registerCss("#{$id} .thumbnail-image { background-image: url('{$product->firstVariant->thumbnailImage()}');}");
?>
<div class="thumbnail" id="<?= $id ?>">
    <div class="thumbnail-image">
        <div class="product-description"><?=$product->description?></div>
        <a href="<?= $product->firstVariant->detailUrl() ?>"><span class="sr-only"><?= Yii::t('shop', 'More') ?></span></a>
    </div>
    <div class="caption">
        <h3 class="product-title"><?= $product->title ?></h3>
        <div class="variant-price"><?=Yii::$app->formatter->asCurrency($product->firstVariant->getActualPrice(), $this->context->module->currency)?></div>
        <?php
        echo Html::ul($product->activeVariants, [
            'item' => function ($variant) use ($id) {
                /** @var Variant $variant */
                return Html::tag('li', Html::a('&nbsp;&nbsp;&nbsp;', $variant->detailUrl(), ['style' => "background-color: {$variant->hex_color};"]), [
                    'data' => [
                        'image' => $variant->thumbnailImage(),
                        'price' => Yii::$app->formatter->asCurrency($variant->price, $this->context->module->currency),
                        'toggle' => 'variant',
                        'target' => '#' . $id
                    ]
                ]);
            },
            'class' => 'variant-link-list'
        ]);
        ?>
        <p></p>
    </div>
</div>
