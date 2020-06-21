<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\components\validators;

use yii\helpers\StringHelper;
use yii\validators\NumberValidator;

class StockValidator extends NumberValidator
{
    public function init()
    {
        $this->min = 1;
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAttribute($model, $attribute)
    {
        $this->max = $model->item->stock;
        parent::validateAttribute($model, $attribute);
    }
}
