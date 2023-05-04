<?php


namespace eluhr\shop\controllers;

use yii\filters\AccessControl;

class BaseFrontendController extends \yii\web\Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $rules = null;
        if (!empty($this->module->frontendAccessRules)) {
            $rulesConfig = $this->module->frontendAccessRules;
            if (is_callable($rulesConfig)) {
                $rules = $rulesConfig($this);
            }
            if (is_array($rulesConfig)) {
                $rules = $rulesConfig;
            }
            if (!empty($rules)) {
                $behaviors['module-frontend-access'] = [
                    'class' => AccessControl::class,
                    'rules' => $rules,
                ];
            }
        }

        return $behaviors;
    }

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = $this->module->frontendLayout;
    }
}
