<?php

namespace eluhr\shop\models\query;

use Yii;

/**
 * This is the ActiveQuery class for [[\eluhr\shop\models\Order]].
 *
 * @see \eluhr\shop\models\Order
 */
class OrderQuery extends \yii\db\ActiveQuery
{
    public function isOwn()
    {
        return $this->andWhere(['NOT', ['user_id' => null]])->andWhere(['user_id' => Yii::$app->getUser()->getId()]);
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\Order[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\Order|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
