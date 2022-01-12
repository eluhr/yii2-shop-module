<?php

namespace eluhr\shop\widgets;

use yii\base\Widget;

/**
 * --- PROPERTIES ---
 *
 * @property \eluhr\shop\models\Variant $variant
 *
 * @author Elias Luhr
 */
class PriceDisplay extends Widget
{
    public $variant;

    public function run()
    {
        return $this->render('price-display', [
            'variant' => $this->variant
        ]);
    }
}
