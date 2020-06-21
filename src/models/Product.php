<?php

namespace eluhr\shop\models;

use eluhr\shop\components\behaviors\FiltersBehavior;
use eluhr\shop\components\traits\FiltersTrait;
use eluhr\shop\models\base\Product as BaseProduct;
use eluhr\shop\models\query\VariantQuery;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * This is the model class for table "sp_product".
 *
 * @property Variant $firstVariant
 * @property mixed $activeVariant
 * @property Variant[] $activeVariants
 */
class Product extends BaseProduct
{
    public const DEFAULT_POPULARITY_BOOST_VALUE = 1;

    use FiltersTrait {
        rules as traitRules;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['filters'] = [
            'class' => FiltersBehavior::class,
            'junctionModel' => TagXProduct::class,
            'typeColumnName' => 'product_id',
            'filterColumnName' => 'tag_id'
        ];
        return $behaviors;
    }

    public function rules()
    {
        $rules = $this->traitRules();
        $rules[] = ['shipping_price', 'required'];
        return $rules;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFirstVariant()
    {
        /** @var VariantQuery $query */
        $query = $this->hasOne(Variant::class, ['product_id' => 'id'])->active()->orderByRank();
        if (ShopSettings::shopGeneralShowOutOfStockVariants() === false) {
            $query->available();
        }
        return $query;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveVariants()
    {
        /** @var VariantQuery $query */
        $query = $this->hasMany(Variant::class, ['product_id' => 'id'])->active()->orderByRank();
        if (ShopSettings::shopGeneralShowOutOfStockVariants() === false) {
            $query->available();
        }
        return $query;
    }

    public function detailUrlRaw()
    {
        return ['/shop/product/detail', 'productId' => $this->id, 'productTitle' => Inflector::slug($this->title)];
    }

    public function detailUrl()
    {
        return Url::to($this->detailUrlRaw());
    }

    public function boost(): bool
    {
        $this->popularity += self::DEFAULT_POPULARITY_BOOST_VALUE;
        return $this->save();
    }
}
