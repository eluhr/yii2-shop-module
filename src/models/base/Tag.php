<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace eluhr\shop\models\base;

use Yii;

/**
 * This is the base-model class for table "sp_tag".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \eluhr\shop\models\Filter[] $facets
 * @property \eluhr\shop\models\Product[] $products
 * @property \eluhr\shop\models\TagXFilter[] $tagXFilters
 * @property \eluhr\shop\models\TagXProduct[] $tagXProducts
 * @property string $aliasModel
 */
abstract class Tag extends \eluhr\shop\models\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sp_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 80],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'name' => Yii::t('shop', 'Name'),
            'created_at' => Yii::t('shop', 'Created At'),
            'updated_at' => Yii::t('shop', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacets()
    {
        return $this->hasMany(\eluhr\shop\models\Filter::className(), ['id' => 'facet_id'])->viaTable('sp_tag_x_filter', ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(\eluhr\shop\models\Product::className(), ['id' => 'product_id'])->viaTable('sp_tag_x_product', ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagXFilters()
    {
        return $this->hasMany(\eluhr\shop\models\TagXFilter::className(), ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagXProducts()
    {
        return $this->hasMany(\eluhr\shop\models\TagXProduct::className(), ['tag_id' => 'id']);
    }


    
    /**
     * @inheritdoc
     * @return \eluhr\shop\models\query\TagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \eluhr\shop\models\query\TagQuery(get_called_class());
    }


}
