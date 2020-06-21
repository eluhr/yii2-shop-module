<?php


namespace eluhr\shop\controllers;

class Controller extends \yii\web\Controller
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = $this->module->frontendLayout;
    }
}
