<?php

namespace eluhr\shop\controllers\dashboard;

use eluhr\shop\controllers\actions\KartikEditable;
use eluhr\shop\models\Order;
use eluhr\shop\models\search\Orders as OrdersSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class OrdersController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['POST'],
            ]
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['update-shipping-link'] = [
            'class' => KartikEditable::class,
            'attribute' => 'shipment_link'
        ];
        $actions['update-invoice-number'] = [
            'class' => KartikEditable::class,
            'attribute' => 'invoice_number'
        ];
        return $actions;
    }

    public function actionIndex($status = Order::STATUS_RECEIVED_PAID)
    {
        $filterModel = new OrdersSearch();

        $this->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Orders')];
        return $this->render('index', [
            'dataProvider' => $filterModel->search(Yii::$app->request->get(), $status),
            'filterModel' => $filterModel,
            'activeStatus' => $status
        ]);
    }


    public function actionView($id)
    {
        $model = Order::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Order not found'));
        }

        $model->setScenario(Order::SCENARIO_INTERNAL_NOTES);

        return $this->render('view', ['order' => $model]);
    }

    public function actionMove($id, $newStatus)
    {
        $model = Order::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Order not found'));
        }

        $model->status = $newStatus;

        $transaction = Yii::$app->db->beginTransaction();
        if (!$model->validate() || !$model->save()) {
            throw new HttpException(500, Yii::t('shop', 'Cannot move order'));
        }

        if ($newStatus === Order::STATUS_SHIPPED && $model->info_mail_has_been_sent === Order::INFO_MAIL_STATUS_NOT_SENT) {
            Yii::info('Sending info mail to: ' . $model->email, __METHOD__);
            if ($model->sendInfoMail()) {
                $transaction->commit();
            } else {
                throw new HttpException(500, Yii::t('shop', 'Something went wrong while sending the info mail'));
                $transaction->rollBack();
            }
        } else {
            $transaction->commit();
        }

        return $this->redirect(['dashboard/orders/index', 'status' => $newStatus]);
    }

    public function actionConfirmOrder($orderId)
    {
        $model = Order::findOne($orderId);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Order not found'));
        }

        if ($model->execute()) {
            Yii::$app->session->addFlash('success', Yii::t('shop', 'Transaction confirmed'));
        } else {
            Yii::$app->session->addFlash('error', Yii::t('shop', 'Something went wrong while comfirming the transaction'));
        }
        return $this->redirect(['dashboard/orders/view', 'id' => $orderId]);
    }

    public function actionSaveInternalNotes($id)
    {
        $model = Order::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Order not found'));
        }

        $model->setScenario(Order::SCENARIO_INTERNAL_NOTES);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('shop', 'Internal notes has been updated'));
        }

        return $this->redirect(['dashboard/orders/view', 'id' => $id]);
    }


    public function actionDelete($id)
    {
        $model = Order::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Order not found'));
        }

        if ($model->deleteWithOrderItems() !== false) {
            Yii::$app->session->addFlash('info', Yii::t('shop', 'Order has been deleted'));
            return $this->redirect(['dashboard/orders/index']);
        }

        Yii::$app->session->addFlash('error', Yii::t('shop', 'Something went wrong while deleting the order'));
        return $this->redirect(['dashboard/orders/view', 'id' => $model->id]);
    }
}
