<?php
/**
 *
 */

namespace eluhr\shop\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class ShopFrontendAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/web/frontend';

    public $js = [
      'js/main.js'
    ];

    public $css = [
      'less/main.less'
    ];

    public $depends = [
        \rmrevin\yii\fontawesome\AssetBundle::class,
        JqueryAsset::class
    ];
}
