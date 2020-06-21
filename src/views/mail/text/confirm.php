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

use eluhr\shop\models\Order;

echo strip_tags(str_replace('<br>', "\n\r", $this->render('../html/confirm', ['order' => $order])));
