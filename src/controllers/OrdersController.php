<?php

namespace eluhr\shop\controllers;

use eluhr\shop\models\Order;
use eluhr\shop\models\OrderSearch;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 */
class OrdersController extends \yii\web\Controller
{
    public $defaultAction = 'all';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
              [
                  'allow' => true,
                  'roles' => ['@'],
                  'actions' => ['all']
              ],
              [
                  'allow' => true,
                  'roles' => ['@', '?'],
                  'actions' => ['detail']
              ]
            ]
        ];
        return $behaviors;
    }

    public function actionAll()
    {
        $this->view->title = \Yii::t('shop', 'All orders');
        $filterModel = new OrderSearch();
        return $this->render('all', [
            'filterModel' => $filterModel,
            'dataProvider' => $filterModel->search(\Yii::$app->getRequest()->get())
        ]);
    }

    public function actionDetail($orderId)
    {
        $model = Order::find()
            ->andWhere(['id' => $orderId])
            ->one();

        if ($model === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Order not found'));
        }

        $this->view->title = \Yii::t('shop', 'Order {orderId}', [
            'orderId' => $model->id
        ]);
        return $this->render('detail', [
            'model' => $model,
            'showCells' => true
        ]);
    }
}
