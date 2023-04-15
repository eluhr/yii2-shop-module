<?php


namespace eluhr\shop\controllers;

use eluhr\shop\assets\ShopProductsConfiguratorBackendAsset;
use eluhr\shop\controllers\dashboard\BaseController;
use eluhr\shop\models\Filter;
use eluhr\shop\models\Product;
use eluhr\shop\models\ShopSettings;
use eluhr\shop\models\Statistics;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\HttpException;

class DashboardController extends BaseController
{

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


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'variant-copy' => ['POST']
            ]
        ];
        return $behaviors;
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

    public function actionDownloadInvoices($dateRange)
    {
        $model = new Statistics([
            'dateRange' => $dateRange
        ]);

        $soldOrders = $model->soldOrders();
        if (empty($soldOrders)) {
            throw new HttpException(404, \Yii::t('shop', 'No invoices found'));
        }

        $exportPath = \Yii::getAlias('@runtime/shop/export/invoices');

        if (FileHelper::createDirectory($exportPath) === false) {
            throw new HttpException(500, \Yii::t('shop', 'Cannot create a export directory for zip download'));
        }
        $zipFile = $exportPath . DIRECTORY_SEPARATOR . 'export-' . date('Y-m-d_H-i-s') . '.zip';

        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE) !== true) {
            throw new HttpException(500, \Yii::t('shop', 'Cannot create a zip file'));
        }

        foreach ($soldOrders as $order) {
            if (!empty($order->invoice_number) && $order->generateInvoice()) {
                $zip->addFile($order->getInvoiceSavePath(), $order->invoiceFileName());
            }
        }

        $zip->close();
        foreach ($soldOrders as $order) {
            if (!empty($order->invoice_number)) {
                unlink($order->getInvoiceSavePath());
            }
        }
        \Yii::$app->response->sendFile($zipFile)->send();
        unlink($zipFile);
        \Yii::$app->end();
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
}
