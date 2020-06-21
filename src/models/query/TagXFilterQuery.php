<?php

namespace eluhr\shop\models\query;

/**
 * This is the ActiveQuery class for [[\eluhr\shop\models\TagXFilter]].
 *
 * @see \eluhr\shop\models\TagXFilter
 */
class TagXFilterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\TagXFilter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\TagXFilter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
