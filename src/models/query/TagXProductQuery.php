<?php

namespace eluhr\shop\models\query;

/**
 * This is the ActiveQuery class for [[\eluhr\shop\models\TagXProduct]].
 *
 * @see \eluhr\shop\models\TagXProduct
 */
class TagXProductQuery extends \yii\db\ActiveQuery
{
    public const ALIAS = 'tXp';
    /**
     * @inheritdoc
     * @return \eluhr\shop\models\TagXProduct[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\TagXProduct|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
