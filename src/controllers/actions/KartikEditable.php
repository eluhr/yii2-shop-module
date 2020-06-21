<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\controllers\actions;

use eluhr\shop\models\Order;
use yii\base\Action;
use yii\web\NotFoundHttpException;

/**
 * --- PUBLIC ---
 *
 * @property string $attribute
*/
class KartikEditable extends Action
{
    /**
     * @var string
     */
    public $attribute;

    public function run()
    {
        $orderId = \Yii::$app->request->post('editableKey');
        $orderIndex = \Yii::$app->request->post('editableIndex');
        $attribute = \Yii::$app->request->post('editableAttribute');
        $order = Order::findOne(['id' => $orderId]);
        if ($order === null) {
            throw new NotFoundHttpException();
        }

        $order->{$this->attribute} = \Yii::$app->request->post($order->formName())[$orderIndex][$attribute];

        $order->save();

        return $this->controller->asJson([
            'output' => $order->shipment_link,
            'message' => $order->errors
        ]);
    }
}
