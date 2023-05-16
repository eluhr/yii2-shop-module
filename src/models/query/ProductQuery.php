<?php

namespace eluhr\shop\models\query;

use eluhr\shop\models\ShopSettings;
use eluhr\shop\models\TagXProduct;
use eluhr\shop\models\Variant;
use yii\db\Expression;
use yii\helpers\HtmlPurifier;

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

    public function isVisible()
    {
        $prefix = '';
        if (isset($this->getTableNameAndAlias()[1]) && $this->getTableNameAndAlias()[1] === self::ALIAS) {
            $prefix = self::ALIAS . '.';
        }

        return $this->andWhere([$prefix . 'hide_in_overview' => 0]);
    }

    public function productIdFilter($id)
    {
        $prefix = '';
        if (isset($this->getTableNameAndAlias()[1]) && $this->getTableNameAndAlias()[1] === self::ALIAS) {
            $prefix = self::ALIAS . '.';
        }

        return $this->andWhere([$prefix . 'id' => $id]);
    }

    /**
     * shorthand for $this->moreThanOneVariantActive()->isVisible()->active()
     * useful to get query for products that:
     * - are visible and active
     * - and have min. one active variant
     *
     * @return ProductQuery
     */
    public function online()
    {
        return $this->moreThanOneVariantActive()->isVisible()->active();
    }

    /**
     * get query for products with min. one active variant
     *
     * @return ProductQuery
     */
    public function moreThanOneVariantActive()
    {
        $query =
            $this->alias(self::ALIAS)
                // always set p.* or p.id as first col as we need to be able to run query with column() which returns the "first" col in result-set
                ->select([self::ALIAS . '.*'])
                ->innerJoin(
                    [
                        VariantQuery::ALIAS => Variant::tableName()
                    ],
                    [
                        self::ALIAS . '.id' => new Expression(VariantQuery::ALIAS . '.product_id'),
                        VariantQuery::ALIAS . '.is_online' => new Expression(1)
                    ]
                )
                ->groupBy(VariantQuery::ALIAS . '.product_id');
        if (ShopSettings::shopGeneralShowOutOfStockVariants() === false) {
            $query->andWhere(['>', VariantQuery::ALIAS . '.stock', 0]);
        }
        return $query;
    }

    public function withVariantPriceRange()
    {
        return $this->addSelect([
                             'max_price' => new Expression('MAX(v.price)'),
                             'min_price' => new Expression('MIN(v.price)'),
                         ]);
    }

    /**
     * add check constraints to reduce result to given tagIds
     *
     * @param array $tagIds
     *
     * @return ProductQuery
     */
    public function hasTagsAssigned(array $tagIds)
    {
        if (!empty($tagIds)) {
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

        }

        return $this;
    }

    public function orderByRank()
    {
        return $this->orderBy(['rank' => SORT_ASC]);
    }

    public function fullTextSearch($q)
    {
        $searchTerm = HtmlPurifier::process(strip_tags($q));
        if (!empty($searchTerm) && ShopSettings::shopGeneralShowSearch()) {
            $this->addSelect([
                'searchableTitle' => new Expression('GROUP_CONCAT(v.title)'),
                'searchableDescription' => new Expression('GROUP_CONCAT(v.description)'),
                'searchableSku' => new Expression('GROUP_CONCAT(v.sku)')
            ]);
            $this->andHaving([
                'OR',
                ['LIKE', 'p.[[title]]', $searchTerm],
                ['LIKE', 'p.[[description]]', $searchTerm],
                ['LIKE', '[[searchableTitle]]', $searchTerm],
                ['LIKE', '[[searchableDescription]]', $searchTerm],
                ['LIKE', '[[searchableSku]]', $searchTerm],
            ]);
        }
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
