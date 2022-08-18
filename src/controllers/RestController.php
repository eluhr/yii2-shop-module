<?php

namespace eluhr\shop\controllers;

use eluhr\shop\models\Variant;
use yii\web\NotFoundHttpException;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 */
class RestController extends \yii\rest\Controller
{

    protected function verbs()
    {
        $verbs = parent::verbs();
        $verbs['configure-variant'] = ['POST'];
        return $verbs;
    }

    public function actionConfigureVariant($variantId)
    {
        $variant = Variant::find()->active()->andWhere(['id' => $variantId])->one();
        if ($variant === null) {
            throw new NotFoundHttpException(\Yii::t('shop', 'Variant not found'));
        }

        /** @var \eluhr\shop\components\ShoppingCart $shoppingCart */
        $shoppingCart = \Yii::$app->shoppingCart;
        return [
            'position' => $shoppingCart->getPositions()
        ];
    }
}
