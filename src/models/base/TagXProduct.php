<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace eluhr\shop\models\base;

use Yii;

/**
 * This is the base-model class for table "sp_tag_x_product".
 *
 * @property integer $tag_id
 * @property integer $product_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \eluhr\shop\models\Product $product
 * @property \eluhr\shop\models\Tag $tag
 * @property string $aliasModel
 */
abstract class TagXProduct extends \eluhr\shop\models\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sp_tag_x_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'product_id'], 'required'],
            [['tag_id', 'product_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['tag_id', 'product_id'], 'unique', 'targetAttribute' => ['tag_id', 'product_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => \eluhr\shop\models\Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => \eluhr\shop\models\Tag::className(), 'targetAttribute' => ['tag_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => Yii::t('shop', 'Tag ID'),
            'product_id' => Yii::t('shop', 'Product ID'),
            'created_at' => Yii::t('shop', 'Created At'),
            'updated_at' => Yii::t('shop', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(\eluhr\shop\models\Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(\eluhr\shop\models\Tag::className(), ['id' => 'tag_id']);
    }


    
    /**
     * @inheritdoc
     * @return \eluhr\shop\models\query\TagXProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \eluhr\shop\models\query\TagXProductQuery(get_called_class());
    }


}
