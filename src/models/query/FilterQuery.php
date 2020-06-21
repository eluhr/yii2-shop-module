<?php

namespace eluhr\shop\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\eluhr\shop\models\Filter]].
 *
 * @see \eluhr\shop\models\Filter
 */
class FilterQuery extends \yii\db\ActiveQuery
{
    /**
     * @return ActiveQuery
     */
    public function orderByRank()
    {
        /** @var ActiveQuery $this */
        return $this->orderBy(['rank' => SORT_ASC]);
    }

    public function active()
    {
        return $this->andWhere(['is_online' => 1]);
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\Filter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\Filter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
