<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @var ShoppingCartProduct $position
 */

use eluhr\shop\controllers\ShoppingCartController;
use eluhr\shop\models\DiscountCode;
use eluhr\shop\models\ShoppingCartModify;
use eluhr\shop\models\ShoppingCartProduct;
use eluhr\shop\widgets\PriceDisplay;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

if ($position->item() instanceof DiscountCode) {
    $notIsInventoryIndependent = false;
} else {
    $notIsInventoryIndependent = !$position->item()->product->is_inventory_independent;
}
?>
<tr>
    <td>
        <div class="btn-group">
            <?php
            ActiveForm::begin([
                'action' => [
                    'update-quantity',
                    'action' => ShoppingCartController::UPDATE_QUANTITY_REMOVE,
                    'positionId' => $position->positionId
                ]
            ]);
            echo Html::submitButton(FA::icon(FA::_TRASH_O), ['class' => 'btn btn-xs btn-danger', 'data-action' => ShoppingCartController::UPDATE_QUANTITY_REMOVE]);
            ActiveForm::end();
            ?>
        </div>
        <?= Html::tag('span', $position->item()->label . ($position->extraInfo ? ' - ' . $position->extraInfo : ''), ['class' => 'product-name']); ?>
    </td>
    <td>
        <?php
        if ($position->isDiscount === false) {
            echo PriceDisplay::widget(['variant' => $position->item()]);
        }
        ?>
    </td>
    <td>
        <?php if ($position->isDiscount === false): ?>
            <div class="btn-toolbar">
                <?php
                ActiveForm::begin([
                    'action' => [
                        'update-quantity',
                        'action' => ShoppingCartController::UPDATE_QUANTITY_DECREASE,
                        'positionId' => $position->positionId
                    ]
                ]);
                echo Html::submitButton(FA::icon(FA::_MINUS), ['class' => 'btn btn-default','data-action' => ShoppingCartController::UPDATE_QUANTITY_DECREASE]);
                ActiveForm::end();
                ?>
                <div class="btn">
                    <?= Html::tag('span', $position->getQuantity(), ['class' => 'quantity']); ?>
                </div>
                <?php
                ActiveForm::begin([
                    'action' => [
                        'update-quantity',
                        'action' => ShoppingCartController::UPDATE_QUANTITY_INCREASE,
                        'positionId' => $position->positionId
                    ]
                ]);
                echo Html::submitButton(FA::icon(FA::_PLUS), ['class' => 'btn btn-default', 'disabled' => $position->getQuantity() >= $position->item()->stock && $notIsInventoryIndependent,'data-action' => ShoppingCartController::UPDATE_QUANTITY_INCREASE]);
                ActiveForm::end();
                ?>
            </div>
            <?php

            $this->beginBlock('button');
            ActiveForm::begin([
                'action' => [
                    'update-quantity',
                    'action' => ShoppingCartController::UPDATE_QUANTITY_ADJUST,
                    'positionId' => $position->positionId
                ]
            ]);
            echo Html::submitButton(Yii::t('shop', 'Anzahl anpassen', [], 'de'), ['class' => 'btn btn-xs btn-warning', 'disabled' => $position->getQuantity() >= ShoppingCartModify::MAX_QUANTITY && $notIsInventoryIndependent]);
            ActiveForm::end();
            $this->endBlock();
            if ($position->item()->stock < $position->getQuantity() && $notIsInventoryIndependent) {
                echo Html::tag('p', Yii::t('shop', 'Momentan stehen leider weniger Artikel zur Verfügung als im Warenkorb vorhanden. {button}', ['button' => $this->blocks['button']], 'de'), ['class' => 'text-warning']);
            }
            ?>
        <?php endif; ?>
    </td>
    <td>
        <?php
        if ($position->isDiscount) {
            echo '-' . $position->item()->prettyValue();
        } else {
            echo Yii::$app->formatter->asCurrency($position->cost, $this->context->module->currency);
        }
        ?>
    </td>
</tr>
