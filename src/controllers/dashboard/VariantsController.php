<?php

namespace eluhr\shop\controllers\dashboard;

use eluhr\shop\models\Variant;
use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class VariantsController extends BaseController
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

    public function actionEdit($id = null, $product_id = null)
    {
        $model = Variant::findOne(['id' => $id]);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Variant not found'));
        }

        if ($model === null) {
            $model = new Variant([
                'product_id' => $product_id
            ]);
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('shop', 'Saved variant'));
            return $this->redirect(['dashboard/variants/edit', 'id' => $model->id]);
        }

        $this->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Products'), 'url' => ['dashboard/products/index']];
        $this->view->params['breadcrumbs'][] = ['label' => $model->product->title, 'url' => ['dashboard/products/edit', 'id' => $model->product_id]];
        $this->view->params['breadcrumbs'][] = ['label' => $model->isNewRecord ? Yii::t('shop', 'New Variant') : $model->title];

        return $this->render('edit', [
            'model' => $model
        ]);
    }

    public function actionCopy($id)
    {
        $model = Variant::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Variant not found'));
        }

        $modelCopy = $model->copy();
        if ($modelCopy !== null) {
            return $this->redirect(['dashboard/variants/edit', 'id' => $modelCopy->id]);
        }
        throw new HttpException(500, Yii::t('shop', 'Error while coping variant'));
    }


    public function actionDelete($id)
    {
        $model = Variant::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Variant not found'));
        }
        if ($model->delete() === false) {
            throw new HttpException(500, Yii::t('shop', 'Error while deleting variant'));
        }

        return $this->redirect(['dashboard/products/edit', 'id' => $model->product_id]);
    }
}
