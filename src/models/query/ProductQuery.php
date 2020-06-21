<?php

namespace eluhr\shop\models\query;

use eluhr\shop\models\ShopSettings;
use eluhr\shop\models\TagXProduct;
use eluhr\shop\models\Variant;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[\eluhr\shop\models\Product]].
 *
 * @see \eluhr\shop\models\Product
 */
class ProductQuery extends \yii\db\ActiveQuery
{
    public const ALIAS = 'p';

    public function active()
    {
        $prefix = '';
        if (isset($this->getTableNameAndAlias()[1]) && $this->getTableNameAndAlias()[1] === self::ALIAS) {
            $prefix = self::ALIAS . '.';
        }

        return $this->andWhere([$prefix . 'is_online' => 1]);
    }

    public function moreThanOneVariantActive()
    {
        $query =
            $this->alias(self::ALIAS)
                ->select(self::ALIAS . '.*')
                ->leftJoin(
                    [
                        VariantQuery::ALIAS => Variant::tableName()
                    ],
                    [
                        self::ALIAS . '.id' => new Expression(VariantQuery::ALIAS . '.product_id'),
                        VariantQuery::ALIAS . '.is_online' => new Expression(1)
                    ]
                )
                ->andWhere(['not', [VariantQuery::ALIAS . '.id' => null]])
                ->groupBy(VariantQuery::ALIAS . '.product_id');
        if (ShopSettings::shopGeneralShowOutOfStockVariants() === false) {
            $query->andWhere(['>',VariantQuery::ALIAS . '.stock', 0]);
        }
        return $query;
    }

    public function hasTagsAssigned(array $tagIds)
    {
        $this->leftJoin(
            [
                TagXProductQuery::ALIAS => TagXProduct::tableName()
            ],
            [
                self::ALIAS . '.id' => new Expression(TagXProductQuery::ALIAS . '.product_id')
            ]
        );
        $this->addSelect(new Expression('GROUP_CONCAT(' . TagXProductQuery::ALIAS . '.tag_id) AS `tags`'));

        $conditions = [];

        foreach ($tagIds as $tagId) {
            if (!empty($tagId)) {
                $conditions[] = new Expression("FIND_IN_SET({$tagId}, `tags`)");
            }
        }

        $this->andHaving(implode(' OR ', $conditions));

        return $this->andHaving(implode(' OR ', $conditions));
    }

    public function orderByRank()
    {
        return $this->orderBy(['rank' => SORT_ASC]);
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\Product[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \eluhr\shop\models\Product|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
