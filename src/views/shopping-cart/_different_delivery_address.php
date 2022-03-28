<?php

use eluhr\shop\models\ShoppingCartCheckout;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @var ActiveForm $form
 * @var ShoppingCartCheckout $shoppingCartCheckout
 */
echo $form->field($shoppingCartCheckout, 'has_different_delivery_address', ['template' => "{input}\n{label}\n{error}"])->checkbox(['data-toggle' => 'collapse', 'data-target' => '#different-delivery-address'], false)->label($shoppingCartCheckout->getAttributeHint('has_different_delivery_address'))->hint(false);
?>
<div id="different-delivery-address"
     class="collapse <?= $shoppingCartCheckout->has_different_delivery_address ? 'in' : '' ?>">
    <div class="row">
        <?= Html::tag('div', $form->field($shoppingCartCheckout, 'delivery_first_name'), ['class' => 'col-xs-12 col-md-4']) ?>
        <?= Html::tag('div', $form->field($shoppingCartCheckout, 'delivery_surname'), ['class' => 'col-xs-12 col-md-4']) ?>
        <?= Html::tag('div', $form->field($shoppingCartCheckout, 'delivery_postal'), ['class' => 'col-xs-12 col-md-4']) ?>
        <?= Html::tag('div', $form->field($shoppingCartCheckout, 'delivery_city'), ['class' => 'col-xs-12 col-md-4']) ?>
        <?= Html::tag('div', $form->field($shoppingCartCheckout, 'delivery_street_name'), ['class' => 'col-xs-12 col-md-4']) ?>
        <?= Html::tag('div', $form->field($shoppingCartCheckout, 'delivery_house_number'), ['class' => 'col-xs-12 col-md-4']) ?>
    </div>
</div>
