<?php

namespace eluhr\shop\models\query;

/**
 * This is the ActiveQuery class for [[\eluhr\shop\models\DiscountCode]].
 *
 * @see \eluhr\shop\models\DiscountCode
 */
class DiscountCodeQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere(['>=', 'expiration_date', date('Y-m-d')]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\DiscountCode[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\DiscountCode|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
