<?php


namespace eluhr\shop\controllers\crud;

use dmstr\web\traits\AccessBehaviorTrait;
use yii\web\Controller as WebController;

class Controller extends WebController
{
    use AccessBehaviorTrait;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = $this->module->backendLayout;
    }
}
