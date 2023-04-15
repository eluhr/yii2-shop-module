<?php

namespace eluhr\shop\controllers\dashboard;

use dmstr\web\traits\AccessBehaviorTrait;
use Yii;
use yii\web\Controller;

abstract class BaseController extends Controller
{

    use AccessBehaviorTrait;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = $this->module->backendLayout;
    }

    public function beforeAction($action)
    {
        Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Dashboard'), 'url' => ['dashboard/index']];
        return parent::beforeAction($action);
    }
}
