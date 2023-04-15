<?php

namespace eluhr\shop\controllers\dashboard;

use eluhr\shop\models\search\Vat as VatSearch;
use eluhr\shop\models\Vat;
use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class VatsController extends BaseController
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
        $filterModel = new VatSearch();

        Yii::$app->user->setReturnUrl(['dashboard/vats/index']);
        $this->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Vats')];
        return $this->render('index', [
            'dataProvider' => $filterModel->search(Yii::$app->request->get()),
            'filterModel' => $filterModel
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = Vat::findOne($id);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Vat not found'));
        }

        if ($model === null) {
            $model = new Vat();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('shop', 'Saved vat'));
            return $this->redirect(['dashboard/vats/edit', 'id' => $model->id]);
        }

        $this->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Vats'), 'url' => ['dashboard/vats/index']];
        $this->view->params['breadcrumbs'][] = ['label' => $model->value];
        return $this->render('edit', [
            'model' => $model
        ]);
    }


    public function actionDelete($id)
    {
        $model = Vat::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Vat not found'));
        }

        if ($model->delete() === false) {
            throw new HttpException(500, Yii::t('shop', 'Error while deleting vat'));
        }

        return $this->redirect(['dashboard/vats/index']);
    }
}
