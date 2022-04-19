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
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class ToggleStatusAction extends Action
{
    public $model;

    public function run($itemId)
    {
        $model = $this->model::findOne($itemId);

        if ($model === null) {
            throw new NotFoundHttpException();
        }

        $model->is_online = (int)!$model->is_online;

        if ($model->save() === false) {
            throw new HttpException(500);
        }

        return $this->controller->redirect(['/shop/dashboard/products-configurator']);
    }
}
