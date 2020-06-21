<?php

use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use kartik\popover\PopoverX;
use eluhr\shop\models\DiscountCode;
use eluhr\shop\models\Order;
use eluhr\shop\models\search\Orders;
use eluhr\shop\models\ShopSettings;
use rmrevin\yii\fontawesome\FA;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Model $filterModel
 * @var string $activeStatus
 * @var View $this
 */

$this->registerJs(
    <<<JS
$('.btn-change').on('click', function () {
    $(this).button('loading')
});

$(document).on('hidden.bs.modal', '#alert', function (event) {
    console.log(event);
    $('.btn-change').button('reset');
});
JS
);
?>
    <div class="form-group">
        <?= Html::a(Yii::t('shop', '{icon} Back', ['icon' => FA::icon(FA::_CHEVRON_LEFT)]), ['index'], ['class' => 'btn btn-default']); ?>
    </div>
    <div class="btn-group">
        <?php
        $receivedCount = Order::find()->andWhere(['status' => Order::STATUS_RECEIVED])->count();
        ?>
        <?= Html::a(Order::getStatusValueLabel(Order::STATUS_RECEIVED) . ($receivedCount > 0 ? Html::tag('span', $receivedCount, ['class' => 'badge bg-red']) : ''), ['', 'status' => Order::STATUS_RECEIVED], ['class' => 'btn btn-' . ($activeStatus === Order::STATUS_RECEIVED ? 'primary' : 'default')]) ?>
        <?= Html::a(Order::getStatusValueLabel(Order::STATUS_RECEIVED_PAID), ['', 'status' => Order::STATUS_RECEIVED_PAID], ['class' => 'btn btn-' . ($activeStatus === Order::STATUS_RECEIVED_PAID ? 'primary' : 'default')]) ?>
        <?= Html::a(Order::getStatusValueLabel(Order::STATUS_IN_PROGRESS), ['', 'status' => Order::STATUS_IN_PROGRESS], ['class' => 'btn btn-' . ($activeStatus === Order::STATUS_IN_PROGRESS ? 'primary' : 'default')]) ?>
        <?= Html::a(Order::getStatusValueLabel(Order::STATUS_SHIPPED), ['', 'status' => Order::STATUS_SHIPPED], ['class' => 'btn btn-' . ($activeStatus === Order::STATUS_SHIPPED ? 'primary' : 'default')]) ?>
        <?= Html::a(Order::getStatusValueLabel(Order::STATUS_FINISHED), ['', 'status' => Order::STATUS_FINISHED], ['class' => 'btn btn-' . ($activeStatus === Order::STATUS_FINISHED ? 'primary' : 'default')]) ?>
    </div>
<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $filterModel,
    'krajeeDialogSettings' => [
            'id' => 'alert'
    ],
    'columns' => [
        [
            'class' => ActionColumn::class,
            'template' => '{backwards} {forwards} {view}',
            'buttons' => [
                'backwards' => function ($url, Order $model) use ($activeStatus) {
                    $newStatus = Order::statusData($activeStatus)['backwards'];
                    if ($newStatus) {
                        return Html::a(FA::icon(FA::_CHEVRON_LEFT), ['order-move', 'id' => $model->id, 'newStatus' => $newStatus], ['class' => 'btn btn-change btn-default', 'data-loading-text' => FA::icon(FA::_SPINNER, ['class' => 'fa-spin'])]);
                    }
                    return Html::tag('div', FA::icon(FA::_CHEVRON_LEFT), ['class' => 'btn disabled btn-default']);
                },
                'forwards' => function ($url, Order $model) use ($activeStatus) {
                    $newStatus = Order::statusData($activeStatus)['forwards'];
                    if ($newStatus) {
                        $confirmText = null;
                        if ($newStatus === Order::STATUS_SHIPPED && $model->info_mail_has_been_sent === Order::INFO_MAIL_STATUS_NOT_SENT) {
                            $confirmText = Yii::t('shop', 'Mit dem verschieben der Bestellung in die Kategorie "{category}" wird zusätzlich eine Info E-Mail an den Kunden versendet. Willst du sicher fortfahren?', ['category' => Order::getStatusValueLabel($newStatus)], 'de');
                        }
                        return Html::a(FA::icon(FA::_CHEVRON_RIGHT), ['order-move', 'id' => $model->id, 'newStatus' => $newStatus], ['class' => 'btn btn-change btn-default', 'data-loading-text' => FA::icon(FA::_SPINNER, ['class' => 'fa-spin']),'data-confirm' => $confirmText]);
                    }
                    return Html::tag('div', FA::icon(FA::_CHEVRON_RIGHT), ['class' => 'btn disabled btn-default']);
                },
                'view' => function ($url, Order $model) {
                    return Html::a(FA::icon(FA::_EYE), ['order-view', 'id' => $model->id], ['class' => 'btn btn-default']);
                }
            ]
        ],
        'id',
        [
            'attribute' => 'type',
            'filter' => Order::optsType(),
            'value' => function ($model) {
                return Order::optsType()[$model->type];
            }
        ],
        [
            'attribute' => 'name',
            'value' => function ($model) {
                return $model->fullName;
            }
        ],
        [
            'label' => Yii::t('shop', 'Products'),
            'value' => function ($model) {
                /** @var Order $model */
                $labels = [];
                foreach ($model->orderItems as $item) {
                    $labels[] = Html::tag('p', Yii::t('shop', '{quantity} times <a href="{link}" target="_blank">{name}</a> for {price}', [
                        'quantity' => $item->quantity,
                        'link' => $item->variant->detailUrl(),
                        'name' => $item->name,
                        'price' => Yii::$app->formatter->asCurrency($item->single_price, Yii::$app->payment->currency)
                    ]));
                }
                return implode(Html::tag('br'), $labels);
            },
            'format' => 'raw'
        ],
        [
            'attribute' => 'code',
            'filter' => DiscountCode::data(),
            'visible' => ShopSettings::shopGeneralEnableDiscountCodes(),
            'value' => function ($model) {
                $discountCode = $model->discountCode;
                if ($discountCode) {
                    return $model->discountCode->label;
                }
                return null;
            }
        ],
        [
            'class' => EditableColumn::class,
            'filter' => [
                Orders::SHIPPING_LINK_FILLED => Yii::t('shop', 'Shipping link filled'),
                Orders::SHIPPING_LINK_NOT_FILLED => Yii::t('shop', 'Shipping link not filled')
            ],
            'attribute' => 'shipment_link',
            'refreshGrid' => false,
            'editableOptions' => [
                'placement' => PopoverX::ALIGN_AUTO_BOTTOM,
                'formOptions' => [
                    'action' => [
                        'update-shipping-link'
                    ]
                ],
            ]
        ],
        [
            'class' => EditableColumn::class,
            'filter' => [
                Orders::INVOICE_NUMBER_FILLED => Yii::t('shop', 'Invoice number filled'),
                Orders::INVOICE_NUMBER_NOT_FILLED => Yii::t('shop', 'Invoice number not filled')
            ],
            'attribute' => 'invoice_number',
            'refreshGrid' => false,
            'editableOptions' => [
                'placement' => PopoverX::ALIGN_AUTO_BOTTOM,
                'formOptions' => [
                    'action' => [
                        'update-invoice-number'
                    ]
                ]
            ]
        ],
        [
            'label' => Yii::t('shop', 'Direct link'),
            'value' => function ($model) {
                return Html::a(Yii::t('shop', 'Direct link'), $model->detailUrl, ['class' => 'btn btn-primary', 'target' => '_blank']);
            },
            'format' => 'raw'
        ]
    ]
]);
