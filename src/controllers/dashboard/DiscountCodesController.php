<?php

namespace eluhr\shop\controllers\dashboard;

use eluhr\shop\models\DiscountCode;
use eluhr\shop\models\search\DiscountCode as DiscountCodeSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class DiscountCodesController extends BaseController
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
        $filterModel = new DiscountCodeSearch();

        Yii::$app->user->setReturnUrl(['dashboard/discount-codes/index']);
        $this->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Discount codes')];
        return $this->render('index', [
            'dataProvider' => $filterModel->search(Yii::$app->request->get()),
            'filterModel' => $filterModel
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = DiscountCode::findOne($id);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Discount code not found'));
        }

        if ($model === null) {
            $model = new DiscountCode();
        }

        $model->setScenario(DiscountCode::SCENARIO_CUSTOM);

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('shop', 'Saved discount code'));
            return $this->redirect(['dashboard/discount-codes/edit', 'id' => $model->id]);
        }

        $this->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Discount codes'), 'url' => ['dashboard/discount-codes/index']];
        $this->view->params['breadcrumbs'][] = ['label' => $model->code];
        return $this->render('edit', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $model = DiscountCode::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Discount code not found'));
        }

        if ($model->delete() === false) {
            throw new HttpException(500, Yii::t('shop', 'Error while deleting discount code'));
        }

        return $this->redirect(['dashboard/discount-codes/index']);
    }
}
