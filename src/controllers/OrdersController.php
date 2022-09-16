<?php

namespace eluhr\shop\controllers;

use eluhr\shop\models\Order;
use eluhr\shop\models\OrderSearch;
use eluhr\shop\models\ShoppingCartModify;
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
                    'actions' => ['detail', 'again']
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
        $model = $this->findModel($orderId);

        $this->view->title = \Yii::t('shop', 'Order {orderId}', [
            'orderId' => $model->id
        ]);
        return $this->render('detail', [
            'model' => $model,
            'showCells' => true
        ]);
    }

    public function actionAgain($orderId)
    {
        $model = $this->findModel($orderId);

        $allAdded = true;
        foreach ($model->orderItems as $orderItem) {
            if ($orderItem->variant !== null) {
                $modifyModel = new ShoppingCartModify([
                    'quantity' => $orderItem->quantity,
                    'variantId' => $orderItem->variant_id,
                    'extraInfo' => $orderItem->extra_info
                ]);
                if (!$modifyModel->updateCurrentShoppingCart()) {
                    $allAdded = false;
                }
            } else {
                $allAdded = false;
            }
        }
        if (!$allAdded) {
            \Yii::$app->session->addFlash('info',\Yii::t('shop','Some items could not be added to the shopping cart. Because they may no longer be available.'));
        }
        return $this->redirect(['shopping-cart/overview']);
    }

    public function findModel($orderId)
    {
        $model = Order::find()
            ->andWhere(['id' => $orderId])
            ->one();

        if ($model === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Order not found'));
        }
        return $model;
    }
}
