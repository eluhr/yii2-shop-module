<?php

namespace eluhr\shop\controllers\dashboard;

use eluhr\shop\models\Filter;
use eluhr\shop\models\search\Filter as FilterSearch;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class FiltersController extends BaseController
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

    /**
     * @return string
     */
    public function actionIndex()
    {
        $filterModel = new FilterSearch();

        $this->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Filters')];
        return $this->render('index', [
            'dataProvider' => $filterModel->search(Yii::$app->request->get()),
            'filterModel' => $filterModel
        ]);
    }


    /**
     * @param $id
     *
     * @throws NotFoundHttpException
     * @return string|Response
     */
    public function actionEdit($id = null)
    {
        $model = Filter::findOne($id);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Page not found'));
        }

        if ($model === null) {
            $model = new Filter();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('shop', 'Entry was saved successfully'));
            return $this->redirect(['edit', 'id' => $model->id]);
        }

        $this->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Filters'), 'url' => ['dashboard/filters/index']];
        $this->view->params['breadcrumbs'][] = ['label' => $model->name];
        return $this->render('edit', [
            'model' => $model
        ]);
    }


    /**
     * @param $id
     *
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @return Response
     */
    public function actionDelete($id)
    {
        $model = Filter::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Page not found'));
        }

        if ($model->delete() === false) {
            throw new HttpException(500, Yii::t('shop', 'Error while deleting filter'));
        }

        return $this->redirect(['dashboard/filters/index']);
    }
}
