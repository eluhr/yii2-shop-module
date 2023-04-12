<?php

namespace eluhr\shop\models\query;

/**
 * This is the ActiveQuery class for [[\eluhr\shop\models\Vat]].
 *
 * @see \eluhr\shop\models\Vat
 */
class VatQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \eluhr\shop\models\Tag[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\Tag|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
