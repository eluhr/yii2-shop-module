<?php
/**
 *
 */

namespace eluhr\shop\assets;

use eluhr\sortablejs\assets\SortableJsAssetBundle;
use yii\web\AssetBundle;

class ShopProductsConfiguratorBackendAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/web/products-configurator';

    public $js = [
      'js/main.js'
    ];

    public $css = [
      'less/main.less'
    ];

    public $depends = [
        SortableJsAssetBundle::class
    ];
}
