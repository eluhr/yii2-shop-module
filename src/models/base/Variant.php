<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace eluhr\shop\models\base;

use Yii;

/**
 * This is the base-model class for table "sp_variant".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $title
 * @property string $thumbnail_image
 * @property integer $is_online
 * @property integer $rank
 * @property string $price
 * @property string $discount_price
 * @property string $vat
 * @property string $hex_color
 * @property integer $stock
 * @property string $sku
 * @property string $description
 * @property string $extra_info
 * @property integer $min_days_shipping_duration
 * @property integer $max_days_shipping_duration
 * @property integer $include_vat
 * @property int $show_affiliate_link
 * @property string $affiliate_link_url
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \eluhr\shop\models\OrderItem[] $orderItems
 * @property \eluhr\shop\models\Order[] $orders
 * @property \eluhr\shop\models\Product $product
 * @property string $aliasModel
 */
abstract class Variant extends \eluhr\shop\models\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sp_variant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'title', 'thumbnail_image', 'rank', 'price', 'hex_color'], 'required'],
            [['product_id', 'is_online', 'rank', 'stock','min_days_shipping_duration','max_days_shipping_duration','show_affiliate_link'], 'integer'],
            [['price','discount_price','vat'], 'number'],
            [['description', 'extra_info'], 'string'],
            [['affiliate_link_url', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 80],
            [['thumbnail_image', 'sku'], 'string', 'max' => 128],
            [['hex_color'], 'string', 'max' => 9],
            [['include_vat'], 'boolean'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => \eluhr\shop\models\Product::className(), 'targetAttribute' => ['product_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'product_id' => Yii::t('shop', 'Product ID'),
            'title' => Yii::t('shop', 'Title'),
            'thumbnail_image' => Yii::t('shop', 'Thumbnail Image'),
            'is_online' => Yii::t('shop', 'Is Online'),
            'rank' => Yii::t('shop', 'Rank'),
            'price' => Yii::t('shop', 'Price'),
            'discount_price' => Yii::t('shop', 'Discount Price'),
            'vat' => Yii::t('shop', 'VAT'),
            'hex_color' => Yii::t('shop', 'Hex Color'),
            'stock' => Yii::t('shop', 'Stock'),
            'sku' => Yii::t('shop', 'Sku'),
            'description' => Yii::t('shop', 'Description'),
            'extra_info' => Yii::t('shop', 'Extra Info'),
            'min_days_shipping_duration' => Yii::t('shop', 'Min Days Shipping Duration'),
            'max_days_shipping_duration' => Yii::t('shop', 'Max Days Shipping Duration'),
            'include_vat' => Yii::t('shop', 'VAT Included'),
            'show_affiliate_link' => Yii::t('shop', 'Show Affiliate Link'),
            'affiliate_link_url' => Yii::t('shop', 'Affiliate Link URL'),
            'created_at' => Yii::t('shop', 'Created At'),
            'updated_at' => Yii::t('shop', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(\eluhr\shop\models\OrderItem::className(), ['variant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(\eluhr\shop\models\Order::className(), ['id' => 'order_id'])->viaTable('sp_order_item', ['variant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(\eluhr\shop\models\Product::className(), ['id' => 'product_id']);
    }


    
    /**
     * @inheritdoc
     * @return \eluhr\shop\models\query\VariantQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \eluhr\shop\models\query\VariantQuery(get_called_class());
    }


}
