<?php
/**
 * --- FROM CONTEXT ---
 *
 * @var yii\web\View $this
 * @var \eluhr\shop\models\Variant $variant
 */

$actualPrice = Yii::$app->formatter->asCurrency($variant->getActualPrice(), Yii::$app->getModule('shop')->currency);
?>
<div class="variant-price <?=$variant->getHasDiscount() ? 'has-discount' : ''?>">
    <?php if ($variant->getHasDiscount()): ?>
        <span class="actual-price"><?= $actualPrice ?></span>
        <span class="old-price"><?= Yii::$app->formatter->asCurrency($variant->price, Yii::$app->getModule('shop')->currency) ?></span>
    <?php else: ?>
    <span class="actual-price"><?= $actualPrice ?></span>
    <?php endif ?>
</div>
