<?php
/**
 * @var Statistics $model
 * @var array $chartData
 * @var string $currency
 */

use eluhr\shop\models\Order;
use eluhr\shop\models\ShopSettings;
use eluhr\shop\models\Statistics;
use eluhr\shop\models\Variant;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use zhuravljov\yii\widgets\DateRangePicker;

$this->registerCss(
    <<<CSS
.no-bg {
    background-color: transparent !important;
}
.my-toolbar {
    display: flex;
}

.my-toolbar.even > * {
    flex-grow: 1;
}
.tab-content>.tab-pane {
    display: block;
    height: 0;
    overflow: hidden;
}
.tab-content>.tab-pane.active {
    height: auto;
}
CSS
);

$this->registerJs(
    <<<JS
$('#daterange').on('apply.daterangepicker', function() {
    this.form.submit();
});
JS
);
?>

<h2><?= Yii::t('shop', 'Dashboard') ?></h2>

<hr>

<div class="row">
    <div class="col-xs-12">
        <div class="my-toolbar even">
            <?= Html::a(FA::icon(FA::_FILTER) . ' ' . Yii::t('shop', 'Filters'), ['dashboard/filters/index'], ['class' => 'btn btn-lg btn-default']) ?>
            <?= Html::a(FA::icon(FA::_HASHTAG) . ' ' . Yii::t('shop', 'Tags'), ['dashboard/tags/index'], ['class' => 'btn btn-lg btn-default']) ?>
            <?= Html::a(FA::icon(FA::_CUBES) . ' ' . Yii::t('shop', 'Products'), ['dashboard/products/index'], ['class' => 'btn btn-lg btn-default']) ?>
            <?= Html::a(FA::icon(FA::_LIST) . ' ' . Yii::t('shop', 'Orders'), ['dashboard/orders/index'], ['class' => 'btn btn-lg btn-default']) ?>
            <?= Html::a(FA::icon(FA::_PERCENT) . ' ' . Yii::t('shop', 'Discount codes'), ['dashboard/discount-codes/index'], ['class' => 'btn btn-lg btn-default']) ?>
            <?= Html::a(FA::icon(FA::_PERCENT) . ' ' . Yii::t('shop', 'Vat Values'), ['dashboard/vats/index'], ['class' => 'btn btn-lg btn-default']) ?>
            <?= Html::a(FA::icon(FA::_DASHBOARD) . ' ' . Yii::t('shop', 'Products Configurator'), ['products-configurator'], ['class' => 'btn btn-lg btn-default']) ?>
            <?= Html::a(FA::icon(FA::_WRENCH) . ' ' . Yii::t('shop', 'Settings'), ['settings'], ['class' => 'btn btn-lg btn-default']) ?>
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-xs-12 col-md-4">
        <div class="my-toolbar">
            <?php
            $form = ActiveForm::begin(['method' => 'get']);
            echo $form->field($model, 'dateRange')->widget(
                DateRangePicker::class,
                [
                    'clientOptions' => [
                        'locale' => [
                            'format' => 'DD.MM.YYYY',
                            'separator' => Statistics::DATE_SEPARATOR,
                        ],
                        'minDate' => explode(Statistics::DATE_SEPARATOR, Statistics::defaultDateRange())[Statistics::DATE_MIN_KEY],
                        'maxDate' => explode(Statistics::DATE_SEPARATOR, Statistics::defaultDateRange())[Statistics::DATE_MAX_KEY],
                    ]
                ]
            )->label(false);
            ActiveForm::end();
            echo Html::tag('div', Html::a(FA::icon(FA::_UNDO), ['index'], ['class' => 'btn btn-primary']));
            ?>
        </div>
    </div>
    <div class="col-xs-8">
        <div class="btn-group pull-right">
            <?= Html::tag('div', Html::a(Yii::t('shop', 'Download invoices {icon}', ['icon' => FA::icon(FA::_DOWNLOAD)]), ['download-invoices', 'dateRange' => $model->dateRange], ['class' => 'btn btn-primary'])); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-4">
        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Sales') ?></h3>
            </div>
            <div class="box-body">
                <h2><?= Yii::$app->formatter->asCurrency($model->sales(), $currency) ?></h2>
                <small><?= Yii::t('shop', 'Including discounts') ?></small>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Average Order') ?></h3>
            </div>
            <div class="box-body">
                <h2><?= Yii::$app->formatter->asCurrency($model->averageOrderTotal(), $currency) ?></h2>
                <small><?= Yii::t('shop', 'Including discounts') ?></small>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-4">
        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Shipping') ?></h3>
            </div>
            <div class="box-body">
                <h2><?= Yii::$app->formatter->asCurrency($model->shipping(), $currency) ?></h2>
                <small>&nbsp;</small>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Orders') ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h2><?= $model->ordersDetail()['guest'] ?? 0 ?></h2>
                        <small><?= Yii::t('shop', 'Number of orders placed as guest') ?></small>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <h2><?= $model->ordersDetail()['user'] ?? 0 ?></h2>
                        <small><?= Yii::t('shop', 'Number of orders placed as logged in user') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Products Sold') ?></h3>
            </div>
            <div class="box-body">
                <h2><?= $model->productsSold() ?></h2>
                <small><?= Yii::t('shop', 'Number of sold products') ?></small>
            </div>
        </div>
    </div>

    <?php if (ShopSettings::shopGeneralEnableDiscountCodes()): ?>
    <div class="col-xs-12 col-md-4">
        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Discount code uses') ?></h3>
            </div>
            <div class="box-body">
                <h2><?= $model->discountCodes() ?></h2>
                <small>&nbsp;</small>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>
<hr>
<div class="row">
    <div class="col-xs-12 col-md-4">
        <?php if(!empty($model->topSeller())): ?>
        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Top seller') ?></h3>
            </div>
            <div class="box-body">
                <?= Html::ol($model->topSeller(), [
                    'item' => function ($item) {
                        /** @var Variant $model */
                        $model = $item['model'];
                        return Html::tag('li', Html::a($model->label . ' (' . $item['count'] . ')', $model->detailUrl()));
                    }
                ]) ?>
            </div>
        </div>
        <?php endif ?>
        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Informations') ?></h3>
            </div>
            <div class="box-body">
                <div class="list-group">
                    <?=Html::a(Yii::t('shop', '<b>{count}</b> orders to fulfill', ['count' => Order::find()->andWhere(['status' => Order::STATUS_RECEIVED_PAID])->count()]), ['orders','status' => Order::STATUS_RECEIVED_PAID], ['class' => 'list-group-item'])?>
                    <?=Html::a(Yii::t('shop', '<b>{count}</b> orders in progress', ['count' => Order::find()->andWhere(['status' => Order::STATUS_IN_PROGRESS])->count()]), ['orders','status' => Order::STATUS_IN_PROGRESS], ['class' => 'list-group-item'])?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Orders by day') ?></h3>
            </div>
            <div class="box-body">
                <div class="tab-content">
                    <?= $this->render('stats/_orders_by_day', ['data' => $model->ordersByDay()]) ?>
                </div>

            </div>
        </div>
    </div>
    <?php if (ShopSettings::shopGeneralEnableDiscountCodes()): ?>
    <div class="col-xs-12 col-md-4">
        <div class="box box-solid box-default">
            <div class="box-header no-bg">
                <h3 class="box-title"><?= Yii::t('shop', 'Discount Code Usages') ?></h3>
            </div>
            <div class="box-body">
                <?= $this->render('stats/_discount_code_usages', ['data' => $model->discountCodeUsages()]); ?>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>
