<?php


namespace eluhr\shop\controllers;

use eluhr\shop\models\Filter;
use eluhr\shop\models\form\Filter as FilterForm;
use eluhr\shop\models\search\ProductFinder;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $filters = Filter::find()->orderByRank()->active()->all();
        $filterForm = new FilterForm();

        $this->view->title = \Yii::t('shop', '__SHOP_OVERVIEW_TITLE__');

        $filterParams = [];
        if ($filterForm->load(\Yii::$app->request->get()) && $filterForm->validate()) {
            $filterParams = [
                'tagIds' => $filterForm->tagIds(),
                'q' => $filterForm->q
            ];
        }

        $finder = new ProductFinder();
        return $this->render('index', [
            'finder' => $finder,
            'dataProvider' => $finder->search($filterParams),
            'filters' => $filters,
            'filterForm' => $filterForm,
        ]);
    }
}
