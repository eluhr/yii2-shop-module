<?php

use dosamigos\chartjs\ChartJs;

/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

?>
<div class="chart-container">
    <?= ChartJs::widget([
        'id' => 'salesByDay',
        'type' => 'line',
        'options' => [
            'responsive' => true,
            'maintainAspectRatio' => false
        ],
        'clientOptions' => [
            'legend' => [
                'display' => false
            ],
            'scales' => [
                'xAxes' => [
                    [
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => Yii::t('shop', 'Datum'),
                        ]
                    ]
                ],
                'yAxes' => [
                    [
                        'ticks' => [
                            'stepSize' => 1,
                            'beginAtZero' => true
                        ],
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => Yii::t('shop', 'Amount in {currency}', ['currency' => Yii::$app->payment->currency]),
                        ]
                    ]
                ]
            ]
        ],
        'data' => [
            'labels' => $data['labels'],
            'datasets' => [
                [
                    'fill' => false,
                    'data' => $data['data'],
                    'borderColor' => 'rgb(60,141,188)',
                ]
            ]
        ]
    ]) ?>
</div>
