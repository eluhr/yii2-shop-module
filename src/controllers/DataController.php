<?php


namespace eluhr\shop\controllers;

use eluhr\shop\controllers\actions\AddTagAction;
use eluhr\shop\controllers\actions\SortAction;
use eluhr\shop\controllers\actions\ToggleStatusAction;
use eluhr\shop\controllers\crud\Controller as WebCrudController;
use eluhr\shop\models\EmailTemplate;
use eluhr\shop\models\Filter;
use eluhr\shop\models\Product;
use eluhr\shop\models\TagXFilter;
use eluhr\shop\models\TagXProduct;
use eluhr\shop\models\Variant;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class DataController extends WebCrudController
{
    public $enableCsrfValidation = false;

    public function actions()
    {
        $actions = parent::actions();
        $actions['add-tag-to-filter'] = [
            'class' => AddTagAction::class,
            'itemIdAttribute' => 'facet_id',
            'model' => TagXFilter::class
        ];
        $actions['add-tag-to-product'] = [
            'class' => AddTagAction::class,
            'itemIdAttribute' => 'product_id',
            'model' => TagXProduct::class
        ];
        $actions['sort-products'] = [
            'class' => SortAction::class,
            'model' => Product::class
        ];
        $actions['sort-filters'] = [
            'class' => SortAction::class,
            'model' => Filter::class
        ];
        $actions['sort-variants'] = [
            'class' => SortAction::class,
            'model' => Variant::class
        ];
        $actions['toggle-product-status'] = [
            'class' => ToggleStatusAction::class,
            'model' => Product::class
        ];
        $actions['toggle-filter-status'] = [
            'class' => ToggleStatusAction::class,
            'model' => Filter::class
        ];
        return $actions;
    }

    public function actionSortFilterTags()
    {
        $data = json_decode(\Yii::$app->request->rawBody, true);

        $itemId = $data['itemId'];
        $items = $data['items'];

        $success = true;
        $errors = [];

        $rank = 0;

        foreach ($items as $tagId) {
            $model = TagXFilter::findOne(['tag_id' => $tagId, 'facet_id' => $itemId]);
            if ($model === null) {
                $success = false;
                break;
            }
            $model->rank = $rank;
            if ($model->save()) {
                $rank++;
            } else {
                $success = false;
                $errors = $model->errors;
                break;
            }
        }

        return $this->asJson([
            'success' => $success,
            'errors' => $errors
        ]);
    }

    public function actionRemoveTag()
    {
        $data = json_decode(\Yii::$app->request->rawBody, true);

        $tagId = $data['tagId'];
        $itemId = $data['itemId'];
        $modelName = $data['model'];

        switch ($modelName) {
            case Product::class:
                $modelClass = TagXProduct::class;
                $attribute = 'product_id';
                break;
            case Filter::class:
                $modelClass = TagXFilter::class;
                $attribute = 'facet_id';
                break;
            default:
                $modelClass = false;
                $attribute = false;
        }
        if ($modelClass) {
            /** @var ActiveRecord $modelClass */
            $model = $modelClass::findOne([$attribute => $itemId, 'tag_id' => $tagId]);

            if ($model === null) {
                $success = false;
            } else {
                $success = $model->delete() !== false;
            }
        } else {
            $success = false;
        }

        return $this->asJson([
            'success' => $success,
            'errors' => []
        ]);
    }
}
