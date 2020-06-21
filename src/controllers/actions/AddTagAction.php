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

class AddTagAction extends Action
{
    public $itemIdAttribute;
    public $model;

    public function run()
    {
        $data = json_decode(\Yii::$app->request->rawBody, true);

        $tagId = $data['tagId'];
        $itemId = $data['itemId'];

        $config = [
            'tag_id' => $tagId,
            $this->itemIdAttribute => $itemId
        ];


        $model = $this->model::findOne($config);

        if ($model === null) {
            $model = new $this->model($config);
            $success = $model->save();
            $errors = $model->errors;
        } else {
            $success = true;
            $errors = [];
        }

        return $this->controller->asJson([
            'success' => $success,
            'errors' => $errors
        ]);
    }
}
