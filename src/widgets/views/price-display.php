<?php
/**
 * --- FROM CONTEXT ---
 *
 * @var yii\web\View $this
 * @var \eluhr\shop\models\Variant $variant
 */

$actualPrice = Yii::$app->formatter->asCurrency($variant->getActualPrice(), Yii::$app->getModule('shop')->currency);
?>
<div class="variant-price <?=$variant->getHasDiscount() ? 'has-discount' : ''?>" data-vat="<?php echo $variant->vat?>">
    <?php if ($variant->getHasDiscount()): ?>
        <span class="actual-price"><?= $actualPrice ?></span>
        <span class="old-price"><?= Yii::$app->formatter->asCurrency($variant->getPotentialActualPrice(), Yii::$app->getModule('shop')->currency) ?></span>
    <?php else: ?>
    <span class="actual-price"><?= $actualPrice ?></span>
    <?php endif ?>
</div>
