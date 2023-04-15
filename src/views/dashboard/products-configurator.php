<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @var Product[] $products
 * @var Filter[] $filters
 * @var View $this
 */

use eluhr\shop\models\Filter;
use eluhr\shop\models\Product;
use eluhr\shop\models\Tag;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

?>
<div class="form-group">
    <div class="btn-toolbar">
        <?= Html::a(Yii::t('shop', '{icon} Back', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['index'], ['class' => 'btn btn-default']) ?>
    </div>
</div>

<div class="configurator">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid">
                <div class="box-body">
                    <ul class="nav nav-pills" data-list="all-tags">
                        <?php foreach (Tag::find()->all() as $tag) {
    echo $this->render('_configurator_tab', ['tag' => $tag]);
} ?>
                        <li class="label label-primary">
                            <?= Html::a(Yii::t('shop', '{icon} New', ['icon' => FA::icon(FA::_PLUS)]), ['dashboard/tags/edit'], ['target' => '_blank']) ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4" data-list="filters" data-sort-url="<?= Url::to(['/shop/data/sort-filters']) ?>">
            <?php foreach ($filters as $filter): ?>
                <div class="box box-solid box-default" data-item="filter" data-item-id="<?= $filter->id ?>">
                    <div class="box-header">
                        <h3 class="box-title"><?= Html::a(FA::icon(FA::_CIRCLE, ['class' => $filter->is_online ? 'text-success' : 'text-danger']), ['/shop/data/toggle-filter-status', 'itemId' => $filter->id]) ?> <?= $filter->name ?></h3>
                        <div class="box-tools pull-right">
                            <?= Html::a(FA::icon(FA::_PENCIL), ['dashboard/filters/edit','id' => $filter->id], ['class' => 'btn btn-box-tool','target' => '_blank']) ?>
                        </div>
                    </div>
                    <div class="box-footer">
                        <ul class="nav nav-pills nav-stacked" data-list="filter-tags" data-item-id="<?= $filter->id ?>"
                            data-target-url="<?= Url::to(['/shop/data/add-tag-to-filter']) ?>"
                            data-sort-url="<?= Url::to(['/shop/data/sort-filter-tags']) ?>">
                            <?php
                            foreach ($filter->getTagXFilters()->orderBy(['rank' => SORT_ASC])->all() as $tagXFilter) {
                                echo $this->render('_configurator_tab', ['tag' => $tagXFilter->tag, 'model' => $tagXFilter->facet]);
                            } ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-xs-12 col-md-8">
            <div class="product-row" data-list="products" data-sort-url="<?= Url::to(['/shop/data/sort-products']) ?>">
                <?php foreach ($products as $product): ?>
                    <div class="product-column" data-item="product" data-item-id="<?= $product->id ?>">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                                <h3 class="box-title"><?= Html::a(FA::icon(FA::_CIRCLE, ['class' => $product->is_online ? 'text-success' : 'text-danger']), ['/shop/data/toggle-product-status', 'itemId' => $product->id]) ?> <?= $product->title ?></h3>
                                <div class="box-tools pull-right">
                                    <?= Html::a(FA::icon(FA::_PENCIL), ['dashboard/products/edit','id' => $product->id], ['class' => 'btn btn-box-tool','target' => '_blank']) ?>
                                </div>
                            </div>
                            <div class="box-body no-padding">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse"
                                               href="#collapse-product-<?= $product->id ?>">
                                                <?= Yii::t('shop', 'Varianten', [], 'de') ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse-product-<?= $product->id ?>" class="panel-collapse collapse in"
                                         data-change="collapse">
                                        <div class="panel-body">
                                            <ul class="nav nav-stacked" data-list="variants"
                                                data-sort-url="<?= Url::to(['/shop/data/sort-variants']) ?>">
                                                <?php foreach ($product->getVariants()->orderByRank()->all() as $variant): ?>
                                                    <li data-item="variant" data-item-id="<?= $variant->id ?>"><a
                                                                href="#"><?= $variant->title ?></a></li>
                                                <?php endforeach; ?>
                                                <li>
                                                    <?= Html::a(Yii::t('shop', '{icon} New', ['icon' => FA::icon(FA::_PLUS)]), ['dashboard/variants/edit', 'product_id' => $product->id], ['target' => '_blank']) ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse"
                                               href="#collapse-variant-<?= $product->id ?>">
                                                <?= Yii::t('shop', 'Tags', [], 'de') ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse-variant-<?= $product->id ?>" class="panel-collapse collapse in"
                                         data-change="collapse">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked" data-list="product-tags"
                                                data-item-id="<?= $product->id ?>"
                                                data-target-url="<?= Url::to(['/shop/data/add-tag-to-product']) ?>">
                                                <?php foreach ($product->tags as $tag) {
                                echo $this->render('_configurator_tab', ['tag' => $tag, 'model' => $product]);
                            } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
