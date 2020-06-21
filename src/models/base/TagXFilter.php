<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace eluhr\shop\models\base;

use Yii;

/**
 * This is the base-model class for table "sp_tag_x_filter".
 *
 * @property integer $tag_id
 * @property integer $facet_id
 * @property integer $show_in_frontend
 * @property integer $rank
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \eluhr\shop\models\Filter $facet
 * @property \eluhr\shop\models\Tag $tag
 * @property string $aliasModel
 */
abstract class TagXFilter extends \eluhr\shop\models\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sp_tag_x_filter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'facet_id'], 'required'],
            [['tag_id', 'facet_id', 'show_in_frontend', 'rank'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['tag_id', 'facet_id'], 'unique', 'targetAttribute' => ['tag_id', 'facet_id']],
            [['facet_id'], 'exist', 'skipOnError' => true, 'targetClass' => \eluhr\shop\models\Filter::className(), 'targetAttribute' => ['facet_id' => 'id']],
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
            'facet_id' => Yii::t('shop', 'Facet ID'),
            'show_in_frontend' => Yii::t('shop', 'Show In Frontend'),
            'rank' => Yii::t('shop', 'Rank'),
            'created_at' => Yii::t('shop', 'Created At'),
            'updated_at' => Yii::t('shop', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacet()
    {
        return $this->hasOne(\eluhr\shop\models\Filter::className(), ['id' => 'facet_id']);
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
     * @return \eluhr\shop\models\query\TagXFilterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \eluhr\shop\models\query\TagXFilterQuery(get_called_class());
    }


}
