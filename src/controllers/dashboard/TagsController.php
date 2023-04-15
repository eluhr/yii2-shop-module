<?php

namespace eluhr\shop\controllers\dashboard;

use eluhr\shop\models\search\Tag as TagSearch;
use eluhr\shop\models\Tag;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TagsController extends BaseController
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
        $filterModel = new TagSearch();

        $this->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Tags')];
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
        $model = Tag::findOne($id);

        if ($model === null && $id !== null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Page not found'));
        }

        if ($model === null) {
            $model = new Tag();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('shop', 'Entry was saved successfully'));
            return $this->redirect(['edit', 'id' => $model->id]);
        }

        $this->view->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Tags'), 'url' => ['dashboard/tags/index']];
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
        $model = Tag::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('shop', 'Page not found'));
        }

        if ($model->delete() === false) {
            throw new HttpException(500, Yii::t('shop', 'Error while deleting tag'));
        }

        return $this->redirect(['dashboard/tags/index']);
    }
}
