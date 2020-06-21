<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\components\validators;

use yii\base\Model;
use yii\helpers\Html;
use yii\validators\RequiredValidator;

/**
 * Check if checkbox ($depend_attribute) is checked. If so, all attributes in relation are now required.
 *
 * @package eluhr\shop\components\validators
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 *  --- PROPERTIES ---
 *
 * @property string $dependent_attribute
 * @property mixed $check_value
 * @property Model $model
 *
 * --- INHERITED PROPERTIES ---
 *
 * @property \Closure $when
 * @property string $whenClient
 */
class DependencyValidator extends RequiredValidator
{
    const CHECK_VALUE_IS_SET = '1';
    const CHECK_VALUE_IS_NOT_SET = '0';

    public $dependent_attribute;

    /**
     * By changing this value to CHECK_VALUE_IS_NOT_SET, you invert the condition
     */
    public $check_value = self::CHECK_VALUE_IS_SET;
    public $model;


    public function init()
    {
        parent::init();

        $this->message = \Yii::t('shop', 'Feld darf nicht leer sein.');

        $this->when = function ($model) {
            return $model->{$this->dependent_attribute} === $this->check_value;
        };

        $selector = Html::getInputId($this->model, $this->dependent_attribute);
        /**
         * To invert condition on the client side
         */
        $append = $this->check_value === static::CHECK_VALUE_IS_NOT_SET ? '!' : '';
        $this->whenClient = "function (attribute, value) {return {$append}$('#{$selector}').prop('checked');}";
    }
}
