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


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CUSTOM] = [
            'code',
            'percent',
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
            'message' => Yii::t('shop', 'Als codes sind nur alphanummerische Zeichen und oder folgende Zeichen erlaubt: -, _, !, ?'),
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
            'percent',
            'number',
            'min' => 0.01,
            'max' => 100,
            'on' => self::SCENARIO_CUSTOM
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

    public function prettyPercent()
    {
        return Yii::$app->formatter->asPercent($this->percent / 100);
    }

    public function percentLabelHtml()
    {
        return Html::tag('span', '-' . $this->prettyPercent(), ['class' => 'percent-discount-label']);
    }

    public function getLabel()
    {
        return Yii::t('shop', 'Discount Code "{code}" ({percent})', ['code' => $this->code,'percent' => '-' . $this->prettyPercent()]);
    }

    public function getPrice()
    {
        $total = Yii::$app->shoppingCart->totalOfProducts();
        $percent = 1 * ($this->percent / 100);
        return $total * $percent * -1;
    }

    public static function data()
    {
        return ArrayHelper::map(self::find()->all(), 'code', 'label');
    }
}
