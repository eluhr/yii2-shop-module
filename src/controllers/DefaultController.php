<?php


namespace eluhr\shop\controllers;

use eluhr\shop\models\Filter;
use eluhr\shop\models\form\Filter as FilterForm;
use eluhr\shop\models\Product;
use yii\data\ActiveDataProvider;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $filters = Filter::find()->orderByRank()->active()->all();

        $filterForm = new FilterForm();

        $this->view->title = \Yii::t('shop', '__SHOP_OVERVIEW_TITLE__');

        $query = Product::find()->online()->orderByRank();

        if ($filterForm->load(\Yii::$app->request->get())) {
            $query->hasTagsAssigned($filterForm->tagIds());
            $query->fullTextSearch($filterForm->q);
        }

        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => $query
            ]),
            'filters' => $filters,
            'filterForm' => $filterForm,
        ]);
    }
}
