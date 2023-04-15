<?php

namespace eluhr\shop\controllers\dashboard;

use eluhr\shop\models\Product;
use eluhr\shop\models\search\Product as ProductSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class ProductsController extends BaseController
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

    public function actionIndex()
    {
        $filterModel = new ProductSearch();

        Yii::$app->getUser()->setReturnUrl(['dashboard/products/index']);
        $this->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Products')];
        return $this->render('index', [
            'dataProvider' => $filterModel->search(Yii::$app->request->get()),
            'filterModel' => $filterModel
        ]);
    }


    public function actionEdit($id = null)
    {
        $model = Product::findOne($id);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Product not found'));
        }

        if ($model === null) {
            $model = new Product();
        }

        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('shop', 'Saved product'));
            return $this->redirect(['dashboard/products/edit', 'id' => $model->id]);
        }

        $this->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Products'), 'url' => ['dashboard/products/index']];
        $this->view->params['breadcrumbs'][] = ['label' => $model->title];
        return $this->render('edit', [
            'model' => $model
        ]);
    }


    public function actionDelete($id)
    {
        $model = Product::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Product not found'));
        }

        if ((int)$model->getVariants()->count() !== 0) {
            Yii::$app->session->addFlash('error', Yii::t('shop', 'Product has some variants. Please delete them before continuing'));
            return $this->redirect(['product-edit', 'id' => $model->id]);
        }

        if ($model->delete() === false) {
            throw new HttpException(500, Yii::t('shop', 'Error while deleting product'));
        }

        return $this->redirect(['dashboard/products/index']);
    }


    public function actionStatus($id)
    {
        $model = Product::findOne($id);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Product not found'));
        }

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            throw new HttpException(500, Yii::t('shop', 'An error occured'));
        }

        return $this->redirect(['dashboard/products/index']);
    }
}
