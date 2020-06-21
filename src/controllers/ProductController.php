<?php


namespace eluhr\shop\controllers;

use eluhr\shop\models\Product;
use eluhr\shop\models\ShoppingCartModify;
use eluhr\shop\models\Variant;
use yii\data\ActiveDataProvider;
use yii\web\Controller as WebController;
use yii\web\NotFoundHttpException;

class ProductController extends Controller
{
    public function actionDetail($productId, $variantId = null)
    {
        $product = Product::find()->andWhere(['id' => $productId])->active()->one();

        if ($product === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Product not found'));
        }

        if ($variantId) {
            $variant = Variant::find()->andWhere(['id' => $variantId])->active()->one();
        } else {
            $variant = $product->firstVariant;
        }

        if ($variant === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Page not found'));
        }

        $shoppingCartModel = new ShoppingCartModify();
        $shoppingCartModel->quantity = 1;

        $this->view->title = $product->title . ' ' . $variant->title;
        return $this->render('detail', [
            'product' => $product,
            'variant' => $variant,
            'shoppingCartModel' => $shoppingCartModel
        ]);
    }
}
