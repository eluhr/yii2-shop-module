<?php

namespace eluhr\shop\models;

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
        $rules[] = [
            [
                'min_days_shipping_duration',
                'max_days_shipping_duration'
            ],
            'integer',
            'min' => 1,
            'max' => 365
        ];
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
        return \dmstr\willnorrisImageproxy\Url::image($this->thumbnail_image);
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

    public function getExtraInfoList()
    {
        if (empty($this->extra_info)) {
            return [];
        }

        $list = [];
        foreach (explode(';', $this->extra_info) as $item) {
            $list[$item] = $item;
        }

        return $list;
    }

    public function deliveryTimeText(): string
    {
        if (!empty($this->min_days_shipping_duration) && !empty($this->max_days_shipping_duration)) {
            return \Yii::t('shop', 'Delivery time: about {min}-{max} working days.', [
                'min' => $this->min_days_shipping_duration,
                'max' => $this->max_days_shipping_duration
            ]);
        }
        if (!empty($this->min_days_shipping_duration)) {
            return \Yii::t('shop', 'Delivery time: about {min} working days.', [
                'min' => $this->min_days_shipping_duration
            ]);
        }
        return '';
    }

    /**
     * @return float
     */
    public function getActualPrice(): float
    {
        if (!empty($this->discount_price)) {
            return (float)$this->discount_price;
        }
        return (float)$this->price;
    }

    /**
     * @return bool
     */
    public function getHasDiscount(): bool
    {
        return !empty($this->discount_price);
    }
}
