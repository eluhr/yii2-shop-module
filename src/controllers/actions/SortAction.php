<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\controllers\actions;

use yii\base\Action;

class SortAction extends Action
{
    public $model;

    public function run()
    {
        $data = json_decode(\Yii::$app->request->rawBody, true);

        $items = $data['items'];

        $success = true;
        $errors = [];

        $rank = 0;

        foreach ($items as $productId) {
            $model = $this->model::findOne($productId);
            if ($model === null) {
                $success = false;
                break;
            }
            $model->rank = $rank;
            if ($model->save()) {
                $rank++;
            } else {
                $success = false;
                $errors = $model->errors;
                break;
            }
        }

        return $this->controller->asJson([
            'success' => $success,
            'errors' => $errors
        ]);
    }
}
