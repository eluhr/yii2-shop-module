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
        $rules[] = [
            [
                'min_days_shipping_duration',
                'max_days_shipping_duration'
            ],
            'integer',
            'min' => 1,
            'max' => 365
        ];
        $rules[] = [
            'vat',
            'number',
            'min' => 0,
            'max' => 100
        ];
        $rules[] = [
            'vat',
            'required',
            'when' => function (self $model) {
                if (ShopSettings::shopProductShowVat() && empty($model->vat)) {
                    $this->addError('vat', \Yii::t('shop', 'Required value'));
                }
            }
        ];
        $rules[] = [
            'affiliate_link_url',
            'url'
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
            $price = (float)$this->discount_price;
        } else {
            $price = (float)$this->price;
        }

        if (!$this->include_vat) {
            $price *= (($this->vat / 100) + 1);
        }
        return round($price, 2);
    }

    /**
     * @return float
     */
    public function getPotentialActualPrice(): float
    {
        $price = (float)$this->price;
        if (!$this->include_vat) {
            $price *= (($this->vat / 100) + 1);
        }
        return round($price, 2);
    }

    /**
     * @return bool
     */
    public function getHasDiscount(): bool
    {
        return !empty($this->discount_price);
    }

    public function getNetPrice(): ?float
    {
        return round($this->getActualPrice() * ($this->vat / 100), 2);
    }

    public function copy(): ?Variant
    {
        $copy = new Variant();
        $copy->attributes = $this->attributes;
        $copy->title .= ' ' . \Yii::t('shop','(copy)');
        $copy->is_online = 0;
        if ($copy->save()) {
            return $copy;
        }

        \Yii::error($copy->getErrors());
        return null;
    }

    public function getIsAffiliate(): bool
    {
        return $this->show_affiliate_link === 1 && !empty($this->affiliate_link_url);
    }
}
