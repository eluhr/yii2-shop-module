<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @var Tag $tag
 * @var Product|Filter $model
 */

use eluhr\shop\models\Filter;
use eluhr\shop\models\Product;
use eluhr\shop\models\Tag;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<li
        class="label label-primary"
        data-item="tag"
        data-tag-id="<?= $tag->id ?>"
        data-model="<?= isset($model) ? get_class($model) : '' ?>"
        data-item-id="<?= isset($model) ? $model->id : '' ?>"
        data-remove-url="<?= Url::to(['/shop/data/remove-tag']) ?>"
>
    <?= $tag->name ?><?= Html::button('&nbsp;', ['class' => 'btn btn-xs btn-remove btn-danger']) ?>
</li>
