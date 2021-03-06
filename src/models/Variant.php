<?php

namespace eluhr\shop\models;

use dmstr\helpers\ImageProxy;
use eluhr\shop\models\base\Variant as BaseVariant;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * This is the model class for table "sp_variant".
 *
 * @property string $label
 */
class Variant extends BaseVariant
{
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['stock', 'required'];
        return $rules;
    }

    public function getLabel()
    {
        if ($this->isNewRecord) {
            return $this->title;
        }
        return $this->product->title . ' - ' . $this->title;
    }

    /**
     * @return string
     */
    public function thumbnailImage()
    {
        return ImageProxy::getFile($this->thumbnail_image);
    }

    public function detailUrl()
    {
        $url = $this->product->detailUrlRaw();
        $url['variantTitle'] = Inflector::slug($this->title);
        $url['variantId'] = $this->id;
        return Url::to($url);
    }

    /**
     * @param int $quantity
     *
     * @return bool
     */
    public function checkout($quantity)
    {
        $this->stock -= $quantity;
        return $this->save();
    }

    public function fewAvailable(): bool
    {
        return $this->stock <= ShopSettings::shopProductFewAvailableWarning();
    }

    public static function data()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'label');
    }
}
