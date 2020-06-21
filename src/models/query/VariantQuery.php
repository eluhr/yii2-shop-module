<?php

namespace eluhr\shop\models\query;

use eluhr\shop\models\ShopSettings;

/**
 * This is the ActiveQuery class for [[\eluhr\shop\models\Variant]].
 *
 * @see \eluhr\shop\models\Variant
 */
class VariantQuery extends \yii\db\ActiveQuery
{
    public const ALIAS = 'v';

    public function active()
    {
        return $this->andWhere(['is_online' => 1]);
    }
    public function available()
    {
        if (ShopSettings::shopGeneralShowOutOfStockVariants() === false) {
            return $this->andWhere(['>','stock', 0]);
        }
        return $this;
    }

    public function orderByRank()
    {
        return $this->orderBy(['rank' => SORT_ASC]);
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\Variant[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\Variant|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
