<?php


namespace eluhr\shop\controllers;

use eluhr\shop\assets\ShopProductsConfiguratorBackendAsset;
use eluhr\shop\controllers\actions\KartikEditable;
use eluhr\shop\controllers\crud\Controller as WebCrudController;
use eluhr\shop\models\DiscountCode;
use eluhr\shop\models\Filter;
use eluhr\shop\models\Order;
use eluhr\shop\models\Product;
use eluhr\shop\models\search\DiscountCode as DiscountCodeSearch;
use eluhr\shop\models\search\Filter as FilterSearch;
use eluhr\shop\models\search\Orders as OrdersSearch;
use eluhr\shop\models\search\Product as ProductSearch;
use eluhr\shop\models\search\Tag as TagSearch;
use eluhr\shop\models\ShopSettings;
use eluhr\shop\models\Statistics;
use eluhr\shop\models\Tag;
use eluhr\shop\models\Variant;
use yii\helpers\FileHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class DashboardController extends WebCrudController
{
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

    public function beforeAction($action)
    {
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Dashboard'), 'url' => ['index']];
        return parent::beforeAction($action);
    }

    public function actionSettings()
    {
        $setting = new ShopSettings();

        if ($setting->updateData(\Yii::$app->request->post())) {
            return $this->refresh();
        }

        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Settings')];
        return $this->render('settings', ['setting' => $setting]);
    }

    public function actionIndex()
    {
        $model = new Statistics([
            'dateRange' => Statistics::defaultDateRange()
        ]);

        $model->load(\Yii::$app->request->get());

        return $this->render('index', [
            'model' => $model,
            'currency' => $this->module->currency
        ]);
    }

    public function actionDownloadInvoices($dateRange)
    {
        $model = new Statistics([
            'dateRange' => $dateRange
        ]);

        $exportPath = \Yii::getAlias('@runtime/tmp');

        if (FileHelper::createDirectory($exportPath) === false) {
            throw new HttpException(500, \Yii::t('shop', 'Cannot create a export directory for zip download'));
        }
        $zipFile = $exportPath . DIRECTORY_SEPARATOR . 'export-' . date('Y-m-d_H-i-s') . '.zip';

        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE) !== true) {
            throw new HttpException(500, \Yii::t('shop', 'Cannot create a zip file'));
        }

        foreach ($model->soldOrders() as $order) {
            if (!empty($order->invoice_number) && $order->generateInvoice()) {
                $zip->addFile($order->getInvoiceSavePath(), $order->invoiceFileName());
            }
        }

        $zip->close();
        foreach ($model->soldOrders() as $order) {
            if (!empty($order->invoice_number)) {
                unlink($order->getInvoiceSavePath());
            }
        }
        \Yii::$app->response->sendFile($zipFile)->send();
        unlink($zipFile);
        return true;
    }


    public function actionFilters()
    {
        $filterModel = new FilterSearch();

        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Filters')];
        return $this->render('filters', [
            'dataProvider' => $filterModel->search(\Yii::$app->request->get()),
            'filterModel' => $filterModel
        ]);
    }

    public function actionOrders($status = Order::STATUS_RECEIVED_PAID)
    {
        $filterModel = new OrdersSearch();

        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Orders')];
        return $this->render('orders', [
            'dataProvider' => $filterModel->search(\Yii::$app->request->get(), $status),
            'filterModel' => $filterModel,
            'activeStatus' => $status
        ]);
    }

    public function actionProducts()
    {
        $filterModel = new ProductSearch();

        \Yii::$app->user->setReturnUrl(['/' . $this->module->id . '/' . $this->id . '/products']);
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Products')];
        return $this->render('products', [
            'dataProvider' => $filterModel->search(\Yii::$app->request->get()),
            'filterModel' => $filterModel
        ]);
    }

    public function actionTags()
    {
        $filterModel = new TagSearch();

        \Yii::$app->user->setReturnUrl(['/' . $this->module->id . '/' . $this->id . '/tags']);
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Tags')];
        return $this->render('tags', [
            'dataProvider' => $filterModel->search(\Yii::$app->request->get()),
            'filterModel' => $filterModel
        ]);
    }

    public function actionDiscountCodes()
    {
        $filterModel = new DiscountCodeSearch();

        \Yii::$app->user->setReturnUrl(['/' . $this->module->id . '/' . $this->id . '/discount-codes']);
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Discount codes')];
        return $this->render('discount-codes', [
            'dataProvider' => $filterModel->search(\Yii::$app->request->get()),
            'filterModel' => $filterModel
        ]);
    }

    public function actionFilterEdit($id = null)
    {
        $model = Filter::findOne($id);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Filter not found'));
        }

        if ($model === null) {
            $model = new Filter();
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('shop', 'Saved filter'));
            return $this->redirect(['filter-edit', 'id' => $model->id]);
        }

        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Filters'), 'url' => ['filters']];
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => $model->name];
        return $this->render('filter-edit', [
            'model' => $model
        ]);
    }


    public function actionTagEdit($id = null)
    {
        $model = Tag::findOne($id);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Tag not found'));
        }

        if ($model === null) {
            $model = new Tag();
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('shop', 'Saved tag'));
            return $this->redirect(['tag-edit', 'id' => $model->id]);
        }

        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Tags'), 'url' => ['tags']];
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => $model->name];
        return $this->render('tag-edit', [
            'model' => $model
        ]);
    }

    public function actionProductEdit($id = null)
    {
        $model = Product::findOne($id);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Product not found'));
        }

        if ($model === null) {
            $model = new Product();
        }

        $model->loadDefaultValues();

        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('shop', 'Saved product'));
            return $this->redirect(['product-edit', 'id' => $model->id]);
        }

        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Products'), 'url' => ['products']];
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => $model->title];
        return $this->render('product-edit', [
            'model' => $model
        ]);
    }

    public function actionDiscountCodeEdit($id = null)
    {
        $model = DiscountCode::findOne($id);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Discount code not found'));
        }

        if ($model === null) {
            $model = new DiscountCode();
        }

        $model->setScenario(DiscountCode::SCENARIO_CUSTOM);

        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('shop', 'Saved discount code'));
            return $this->redirect(['discount-code-edit', 'id' => $model->id]);
        }

        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Discount codes'), 'url' => ['discount-codes']];
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => $model->code];
        return $this->render('discount-code-edit', [
            'model' => $model
        ]);
    }

    public function actionProductDelete($id)
    {
        $model = Product::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Product not found'));
        }

        if ((int)$model->getVariants()->count() !== 0) {
            \Yii::$app->session->addFlash('error', \Yii::t('shop', 'Product has some variants. Please delete them before continuing'));
            return $this->redirect(['product-edit', 'id' => $model->id]);
        }

        if ($model->delete() === false) {
            throw new HttpException(500, \Yii::t('shop', 'Error while deleting product'));
        }

        return $this->goBack(['products']);
    }

    public function actionFilterDelete($id)
    {
        $model = Filter::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Filter not found'));
        }

        if ($model->delete() === false) {
            throw new HttpException(500, \Yii::t('shop', 'Error while deleting filter'));
        }

        return $this->goBack(['filters']);
    }


    public function actionTagDelete($id)
    {
        $model = Tag::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Tag not found'));
        }

        if ($model->delete() === false) {
            throw new HttpException(500, \Yii::t('shop', 'Error while deleting tag'));
        }

        return $this->goBack(['tags']);
    }

    public function actionProductStatus($id)
    {
        $model = Product::findOne($id);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Product not found'));
        }

        if (!$model->load(\Yii::$app->request->post()) || !$model->save()) {
            throw new HttpException(500, \Yii::t('shop', 'An error occured'));
        }

        return $this->goBack(['products']);
    }

    public function actionVariantEdit($id = null, $product_id = null)
    {
        $model = Variant::findOne(['id' => $id]);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Variant not found'));
        }

        if ($model === null) {
            $model = new Variant([
                'product_id' => $product_id
            ]);
        }


        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('shop', 'Saved variant'));
            return $this->redirect(['variant-edit', 'id' => $model->id]);
        }

        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Products'), 'url' => ['products']];
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => $model->product->title, 'url' => ['product-edit', 'id' => $model->product_id]];
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => $model->isNewRecord ? \Yii::t('shop', 'New Variant') : $model->title];

        return $this->render('variant-edit', [
            'model' => $model
        ]);
    }


    public function actionVariantDelete($id)
    {
        $model = Variant::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Variant not found'));
        }
        if ($model->delete() === false) {
            throw new HttpException(500, \Yii::t('shop', 'Error while deleting variant'));
        }

        return $this->goBack(['product-edit', 'id' => $model->product_id]);
    }


    public function actionDiscountCodeDelete($id)
    {
        $model = DiscountCode::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Discount code not found'));
        }

        if ($model->delete() === false) {
            throw new HttpException(500, \Yii::t('shop', 'Error while deleting discount code'));
        }

        return $this->goBack(['discount-codes']);
    }

    public function actionOrderMove($id, $newStatus)
    {
        $model = Order::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Order not found'));
        }

        $model->status = $newStatus;

        $transaction = \Yii::$app->db->beginTransaction();
        if (!$model->validate() || !$model->save()) {
            throw new HttpException(500, \Yii::t('shop', 'Cannot move order'));
        }

        if ($newStatus === Order::STATUS_SHIPPED && $model->info_mail_has_been_sent === Order::INFO_MAIL_STATUS_NOT_SENT) {
            \Yii::info('Sending info mail to: ' . $model->email, __METHOD__);
            if ($model->sendInfoMail()) {
                $transaction->commit();
            } else {
                throw new HttpException(500, \Yii::t('shop', 'Something went wrong while sending the info mail'));
                $transaction->rollBack();
            }
        } else {
            $transaction->commit();
        }

        return $this->redirect(['orders', 'status' => $newStatus]);
    }

    public function actionProductsConfigurator()
    {
        ShopProductsConfiguratorBackendAsset::register($this->view);
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => \Yii::t('shop', 'Products Configurator')];
        return $this->render(
            'products-configurator',
            [
                'filters' => Filter::find()->orderByRank()->all(),
                'products' => Product::find()->orderByRank()->all()
            ]
        );
    }

    public function actionOrderView($id)
    {
        $model = Order::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Order not found'));
        }

        $model->setScenario(Order::SCENARIO_INTERNAL_NOTES);

        return $this->render('order-view', ['order' => $model]);
    }

    public function actionConfirmOrder($orderId)
    {
        $model = Order::findOne($orderId);

        if ($model === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Order not found'));
        }

        if ($model->execute()) {
            \Yii::$app->session->addFlash('success', \Yii::t('shop', 'Transaction confirmed'));
        } else {
            \Yii::$app->session->addFlash('error', \Yii::t('shop', 'Something went wrong while comfirming the transaction'));
        }
        return $this->redirect(['order-view', 'id' => $orderId]);
    }

    public function actionSaveOrderInternalNotes($id)
    {
        $model = Order::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Order not found'));
        }

        $model->setScenario(Order::SCENARIO_INTERNAL_NOTES);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('shop', 'Internal notes has been updated'));
        }

        return $this->redirect(['order-view', 'id' => $id]);
    }

    public function actionDeleteOrder($id)
    {
        $model = Order::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Order not found'));
        }

        if ($model->deleteWithOrderItems() !== false) {
            \Yii::$app->session->addFlash('info', \Yii::t('shop', 'Order has been deleted'));
            return $this->redirect(['orders']);
        }

        \Yii::$app->session->addFlash('error', \Yii::t('shop', 'Something went wrong while deleting the order'));
        return $this->redirect(['order-view', 'id' => $model->id]);
    }
}
