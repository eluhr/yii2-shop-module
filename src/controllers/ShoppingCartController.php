<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\controllers;

use http\Exception\InvalidArgumentException as HttpInvalidArgumentException;
use eluhr\shop\components\ShoppingCart;
use eluhr\shop\models\DiscountCode;
use eluhr\shop\models\Order;
use eluhr\shop\models\ShoppingCartCheckout;
use eluhr\shop\models\ShoppingCartDiscount;
use eluhr\shop\models\ShoppingCartModify;
use eluhr\shop\models\ShopSettings;
use Yii;
use yii\base\Action;
use yii\base\InvalidArgumentException;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @package eluhr\shop\controllers
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class ShoppingCartController extends Controller
{
    public $defaultAction = 'overview';

    const UPDATE_QUANTITY_INCREASE = 'increase';
    const UPDATE_QUANTITY_DECREASE = 'decrease';
    const UPDATE_QUANTITY_REMOVE = 'remove';
    const UPDATE_QUANTITY_ADJUST = 'adjust';

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verb-filter'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'update-item' => ['POST'],
                'update-quantity' => ['POST'],
                'check-discount-code' => ['POST']
            ]
        ];
        return $behaviors;
    }

    /**
     * @param Action $action the action to be executed.
     *
     * @throws \yii\web\BadRequestHttpException
     * @return bool
     */
    public function beforeAction($action)
    {
        $needsFullShoppingCart = ['overview', 'checkout', 'update-quantity', 'check-discount-code'];

        if (in_array($action->id, $needsFullShoppingCart, true)) {
            /** @var \eluhr\shop\components\ShoppingCart $shoppingCart */
            $shoppingCart = Yii::$app->shoppingCart;

            if ($shoppingCart->isEmpty) {
                Yii::$app->session->addFlash(
                    'info',
                    Yii::t('shop', 'Your shopping cart is currently empty. Please add some products to proceed.')
                );
                $this->redirect(['/' . $this->module->id . '/default/index']);
                return false;
            }
        }

        if ($action->id === 'check-discount-code') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionUpdateItem()
    {
        $model = new \eluhr\shop\models\ShoppingCartModify();

        if (!$model->load(Yii::$app->request->post())) {
            throw new InvalidArgumentException(Yii::t('shop', 'Unable to update shopping cart'));
        }

        $session = Yii::$app->session;
        if ($model->updateCurrentShoppingCart()) {
            $session->addFlash('success', Yii::t('shop', 'Added course to shopping cart'));
        } else {
            $session->addFlash('error', Yii::t('shop', 'Cannot add course to shopping cart'));
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $action
     * @param $positionId
     *
     * @return \yii\web\Response
     */
    public function actionUpdateQuantity($action, $positionId)
    {
        if (!in_array(
            $action,
            [self::UPDATE_QUANTITY_DECREASE, self::UPDATE_QUANTITY_INCREASE, self::UPDATE_QUANTITY_REMOVE, self::UPDATE_QUANTITY_ADJUST],
            true
        )) {
            throw new HttpInvalidArgumentException(Yii::t('shop', 'Action must be either increase or decrease'));
        }

        /** @var ShoppingCart $shoppingCart */
        $shoppingCart = Yii::$app->shoppingCart;

        if (!$shoppingCart->hasPosition($positionId)) {
            throw new HttpInvalidArgumentException(Yii::t(
                'shop',
                'Position does not exist in your current shopping cart'
            ));
        }

        $position = $shoppingCart->getPositionById($positionId);
        if ($position) {
            switch ($action) {
                case self::UPDATE_QUANTITY_INCREASE:
                    $newQuantity = $position->getQuantity() + 1;
                    break;
                case self::UPDATE_QUANTITY_DECREASE:
                    $newQuantity = $position->getQuantity() - 1;
                    break;
                case self::UPDATE_QUANTITY_ADJUST:
                    $newQuantity = $position->item()->stock;
                    break;
                case self::UPDATE_QUANTITY_REMOVE:
                    $newQuantity = 0;
                    break;
                default:
                    $newQuantity = $position->getQuantity();
            }


            if ($newQuantity < ShoppingCartModify::MAX_QUANTITY + 1) {
                $shoppingCart->setQuantity($position, $newQuantity);
            } else {
                Yii::$app->session->addFlash(
                    'info',
                    Yii::t(
                        'shop',
                        'Cannot add more than {maxQuantity} of this course to the shopping cart',
                        ['maxQuantity' => ShoppingCartModify::MAX_QUANTITY]
                    )
                );
            }
        } else {
            throw new HttpInvalidArgumentException(Yii::t(
                'shop',
                'Position does not exist in your current shopping cart'
            ));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @return string
     */
    public function actionOverview()
    {
        $model = new ShoppingCartDiscount();
        $model->check();
        if (Yii::$app->request->isPost) {
            DiscountCode::unuse();
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->apply()) {
                return $this->redirect(['overview']);
            }
        }

        $this->view->title = \Yii::t('shop', '__SHOP_SHOPPING_CART_OVERVIEW_TITLE__');
        return $this->render('overview', [
            'shoppingCartDiscount' => $model
        ]);
    }

    /**
     * @throws HttpException
     */
    public function actionCheckout()
    {
        $model = new ShoppingCartCheckout();

        if ($model->load(Yii::$app->request->post()) && $model->checkout()) {
            if ($model->type !== Order::TYPE_PREPAYMENT) {
                return $this->redirect($model->approvalLink());
            }
            $order = $model->getOrder();
            if ($order === null) {
                throw new HttpException(500, Yii::t('shop', 'Error while checkout', [], 'de'));
            }
            if (!$order->checkout(Order::STATUS_RECEIVED)) {
                throw new HttpException(500, Yii::t('shop', 'Error while updating order. Please contact an admin'));
            }
            Yii::$app->session->addFlash('success', Yii::t('shop', 'Transaction completed. You will receive an email with further instructions'));
            Yii::$app->shoppingCart->removeAll();
            return $this->redirect(['prepayment', 'orderId' => $order->id]);
        }

        $this->view->title = \Yii::t('shop', '__SHOP_SHOPPING_CART_CHECKOUT_TITLE__');
        return $this->render('checkout', ['shoppingCartCheckout' => $model]);
    }

    public function actionPrepayment($orderId)
    {
        $order = Order::find()->andWhere(['id' => $orderId])->andWhere(['type' => Order::TYPE_PREPAYMENT])->one();

        if ($order === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'There is no such order'));
        }

        if ($order->status === Order::STATUS_RECEIVED) {
            $this->view->title = \Yii::t('shop', '__SHOP_SHOPPING_CART_PREPAYMENT_TITLE__');
            return $this->render('prepayment', ['order' => $order]);
        }
        return $this->redirect($order->detailUrl);
    }

    public function actionSuccess($paymentId, $token, $PayerID)
    {
        $order = Order::findOne(['paypal_id' => $paymentId]);

        if ($order === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'There is no such order'));
        }

        $order->paypal_token = $token;
        $order->paypal_payer_id = $PayerID;
        $order->status = Order::STATUS_RECEIVED_PAID;

        if (!$order->checkout()) {
            throw new HttpException(500, Yii::t('shop', 'Error while updating order. Please contact an admin'));
        }
        Yii::$app->session->addFlash('success', Yii::t('shop', 'Transaction completed. You will receive an email with further instructions'));
        Yii::$app->shoppingCart->removeAll();
        return $this->redirect($order->detailUrl);
    }

    public function actionSuccessSaferpay($orderId)
    {
        $order = Order::findOne(['id' => $orderId,'type' => Order::TYPE_SAFERPAY]);

        if ($order === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'There is no such order'));
        }

        $order->status = Order::STATUS_RECEIVED_PAID;

        if (!$order->checkout()) {
            throw new HttpException(500, Yii::t('shop', 'Error while updating order. Please contact an admin'));
        }
        Yii::$app->session->addFlash('success', Yii::t('shop', 'Transaction completed. You will receive an email with further instructions'));
        Yii::$app->shoppingCart->removeAll();
        return $this->redirect($order->detailUrl);
    }

    public function actionSuccessPayrexx($orderId)
    {
        $order = Order::findOne(['id' => $orderId,'type' => Order::TYPE_PAYREXX]);

        if ($order === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'There is no such order'));
        }

        $order->status = Order::STATUS_RECEIVED_PAID;

        if (!$order->checkout()) {
            throw new HttpException(500, Yii::t('shop', 'Error while updating order. Please contact an admin'));
        }
        Yii::$app->session->addFlash('success', Yii::t('shop', 'Transaction completed. You will receive an email with further instructions'));
        Yii::$app->shoppingCart->removeAll();
        return $this->redirect($order->detailUrl);
    }

    public function actionCanceled()
    {
        Yii::$app->session->addFlash('info', Yii::t('shop', 'Transaction canceled'));
        return $this->goHome();
    }

    public function actionCheckDiscountCode()
    {
        if (ShopSettings::shopGeneralEnableDiscountCodes() === false) {
            throw new NotFoundHttpException(Yii::t('shop', 'Page not found'));
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $discountCode = DiscountCode::find()->andWhere(['code' => Yii::$app->request->post('code')])->active()->one();
        if ($discountCode === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Discount code not found'));
        }

        return [
            'success' => true,
            'data' => [
                'percentLabelHtml' => $discountCode->percentLabelHtml()
            ],
            'errors' => $discountCode->errors
        ];
    }

    public function actionOrder($orderId)
    {
        $order = Order::findOne($orderId);

        if ($order === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'There is no such order'));
        }

        $this->view->title = \Yii::t('shop', '__SHOP_ORDER_TITLE__');
        return $this->render('order', ['order' => $order, 'showCells' => true]);
    }

    public function actionInvoice($orderId)
    {
        $order = Order::findOne($orderId);

        if ($order === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'There is no such order'));
        }

        $directory = Yii::getAlias('@runtime/tmp/download');
        FileHelper::createDirectory($directory);
        $filename = $directory . DIRECTORY_SEPARATOR . $order->invoiceFileName();

        if ($order->pdfObject()->saveAs($filename)) {
            return Yii::$app->response->sendFile($filename);
        }
        throw new HttpException(500, Yii::t('shop', 'Something went wrong.'));
    }
}
