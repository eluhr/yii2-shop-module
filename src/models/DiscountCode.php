<?php

namespace eluhr\shop\models;

use Yii;
use \eluhr\shop\models\base\DiscountCode as BaseDiscountCode;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "sp_discount_code".
 *
 * @property float|int $price
 * @property mixed $label
 * @property mixed $title
 * @property int $stock
 */
class DiscountCode extends BaseDiscountCode
{
    public const SESSION_KEY = 'code-used';

    public const SCENARIO_CUSTOM = 'SCENARIO_CUSTOM';

    const TYPE_PERCENT = 1;
    const TYPE_AMOUNT = 2;

    public static function optsTypes()
    {
        return [
            self::TYPE_PERCENT => Yii::t('shop','Percent'),
            self::TYPE_AMOUNT => Yii::t('shop','Fixed value')
        ];
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CUSTOM] = [
            'code',
            'type',
            'value',
            'expiration_date'
        ];
        return $scenarios;
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            'code',
            'match',
            'pattern' => '/^[a-z0-9-_!?]+$/i',
            'message' => Yii::t('shop', 'Als codes sind nur alphanumerische Zeichen und oder folgende Zeichen erlaubt: -, _, !, ?'),
            'on' => self::SCENARIO_CUSTOM
        ];
        $rules[] = [
            'expiration_date',
            'date',
            'format' => 'php:Y-m-d',
            'min' => strtotime('-1 day'),
            'on' => self::SCENARIO_CUSTOM
        ];
        $rules[] = [
            'value',
            'number',
            'min' => 0.01,
            'max' => 100,
            'on' => self::SCENARIO_CUSTOM
        ];
        $rules[] = [
          'type',
          'in',
          'range'=> array_keys(self::optsTypes())
        ];
        return $rules;
    }

    public function beforeValidate()
    {
        if ($this->getScenario() === self::SCENARIO_CUSTOM) {
            $this->expiration_date = date('Y-m-d', strtotime($this->expiration_date));
        }
        return parent::beforeValidate();
    }

    public function isExpired(): bool
    {
        return strtotime($this->expiration_date) < strtotime(date('Y-m-d'));
    }

    public function use(): bool
    {
        if (Yii::$app->session->get(self::SESSION_KEY, false) === $this->code) {
            $this->addError('code', Yii::t('shop', 'You already used this code'));
            return false;
        }
        Yii::$app->session->set(self::SESSION_KEY, $this->code);
        return $this->updateCounters(['used' => 1]);
    }

    public static function unuse()
    {
        Yii::$app->session->set(self::SESSION_KEY, false);
    }

    public function prettyValue()
    {
        if ($this->type === self::TYPE_PERCENT) {
            return Yii::$app->formatter->asPercent($this->value / 100);
        }
        return Yii::$app->formatter->asCurrency($this->value);
    }

    public function percentLabelHtml()
    {
        return Html::tag('span', '-' . $this->prettyValue(), ['class' => 'percent-discount-label']);
    }

    public function getLabel()
    {
        return Yii::t('shop', 'Discount Code "{code}" ({value})', ['code' => $this->code,'value' => '-' . $this->prettyValue()]);
    }

    public function getPrice()
    {
        if ($this->type === self::TYPE_PERCENT) {
            $total = Yii::$app->shoppingCart->totalOfProducts();
            return $total * ($this->value / 100) * -1;
        }
        return $this->value * -1;
    }

    public function getActualPrice()
    {
        return $this->getPrice();
    }

    public static function data()
    {
        return ArrayHelper::map(self::find()->all(), 'code', 'label');
    }
}
