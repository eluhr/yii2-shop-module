<?php
/**
 * @var Order $order
 * @var bool $showCells
 */

use hrzg\widget\widgets\Cell;
use eluhr\shop\models\Order;
use yii\helpers\Html;

?>
<?= $showCells ? Cell::widget(['id' => 'shopping-cart-order-top']) : '' ?>
    <div class="order-view">
        <?php
        echo $this->render('_table_completed', ['order' => $order]);
        ?>
    </div>
<?= $showCells ? Cell::widget(['id' => 'shopping-cart-order-bottom']) : '' ?>