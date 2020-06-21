<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @var Order $order
 */

use hrzg\widget\widgets\Cell;
use eluhr\shop\models\Order;

?>
<?= Cell::widget(['id' => 'shopping-cart-prepayment-top']) ?>
    <div class="prepayment-view">
        <?php
        echo $this->render('_table_completed', ['order' => $order]);
        ?>
    </div>
<?= Cell::widget(['id' => 'shopping-cart-prepayment-bottom']) ?>
