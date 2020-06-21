<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\models;

use eluhr\shop\models\query\OrderQuery;
use yii\base\Model;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class Statistics extends Model
{
    public $dateRange;

    public const DATE_SEPARATOR = ' - ';
    public const DATE_FORMAT = 'd.m.Y';
    public const DATE_MIN_KEY = 0;
    public const DATE_MAX_KEY = 1;

    public static function defaultDateRange()
    {
        $dateFormat = '%' . str_replace('.', '.%', self::DATE_FORMAT);
        $query = new Query();
        $query
            ->select(['DATE_FORMAT(MIN(created_at), "' . $dateFormat . '") AS min', 'DATE_FORMAT(MAX(created_at), "' . $dateFormat . '") AS max'])
            ->from(Order::tableName());
        $row = $query->one();
        $min = $row['min'] ?? date(self::DATE_FORMAT);
        $max = $row['max'] ?? date(self::DATE_FORMAT);
        return $min . self::DATE_SEPARATOR . $max;
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
          'dateRange',
          'required'
        ];
        return $rules;
    }

    public function formName()
    {
        return '';
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['dateRange'] = \Yii::t('shop', 'Date Range');
        return $attributeLabels;
    }

    /**
     * @var Order[]
     */
    private static $orders = [];

    public function sales()
    {
        $total = 0;
        foreach ($this->soldOrders() as $order) {
            $total += $order->totalAmount;
        }

        return $total;
    }

    public function orders()
    {
        return count($this->soldOrders());
    }

    public function productsSold()
    {
        $total = 0;
        foreach ($this->soldOrders() as $order) {
            foreach ($order->orderItems as $orderItem) {
                $total += $orderItem->quantity;
            }
        }
        return $total;
    }

    /**
     * @param string $which Must be either 0 or 1
     *
     * @return string
     */
    protected function dateFromData($which)
    {
        return date(self::DATE_FORMAT, strtotime(explode(self::DATE_SEPARATOR, $this->dateRange)[$which]));
    }


    /**
     * @return OrderQuery
     */
    protected function soldOrdersQuery()
    {
        $query = Order::find()->alias('o')->andWhere(['!=', 'o.status', Order::STATUS_RECEIVED]);
        if (!empty($this->dateRange)) {
            $query->andWhere(['>=', 'o.created_at', date('Y-m-d 00:00:00', strtotime($this->dateFromData(self::DATE_MIN_KEY)))]);
            $query->andWhere(['<=', 'o.created_at', date('Y-m-d 23:59:59', strtotime($this->dateFromData(self::DATE_MAX_KEY)))]);
        }
        return $query;
    }
    /**
     * @return Order[]
     */
    public function soldOrders(): array
    {
        if (empty(self::$orders)) {
            self::$orders = $this->soldOrdersQuery()->all();
        }
        return self::$orders;
    }

    public function averageOrderTotal()
    {
        if ($this->orders() === 0) {
            return 0;
        }
        return round($this->sales() / $this->orders(), 2);
    }

    public function shipping()
    {
        $total = 0;
        foreach ($this->soldOrders() as $order) {
            $total += (float)$order->shipping_price;
        }
        return $total;
    }

    public function discountCodes()
    {
        $total = 0;
        foreach ($this->soldOrders() as $order) {
            if ($order->discount_code_id) {
                $total++;
            }
        }
        return $total;
    }

    public function ordersByDay()
    {
        $query = $this->soldOrdersQuery();
        $models = $query->select(['COUNT(1) as count', 'DATE(created_at) AS date'])
            ->groupBy([new Expression('DATE(created_at)')])
            ->asArray()
            ->all();

        return [
            'labels' => ArrayHelper::getColumn($models, 'date'),
            'data' => ArrayHelper::getColumn($models, 'count')
        ];
    }
    public function salesByDay()
    {
        $query = $this->soldOrdersQuery();
        $models = $query->select(['COUNT(1) as count', 'DATE(created_at) AS date'])
            ->groupBy([new Expression('DATE(created_at)')])
            ->asArray()
            ->all();
        return [
            'labels' => ArrayHelper::getColumn($models, 'date'),
            'data' => ArrayHelper::getColumn($models, 'count')
        ];
    }

    public function discountCodeUsages()
    {
        $labels = [];
        $data = [];
        $tmp = [];
        foreach ($this->soldOrders() as $order) {
            if (!empty($order->discount_code_id) && !in_array($order->discount_code_id, $tmp)) {
                $tmp[] = $order->discount_code_id;
                $labels[] = $order->discountCode->code;
                $data[] = $order->discountCode->used;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function topSeller($limit = 5): array
    {
        $rows = $this->soldOrdersQuery()
            ->addSelect([new Expression('COUNT(variant_id) AS count'), new Expression('variant_id AS variantId')])
            ->leftJoin(OrderItem::tableName(), 'id = order_id')
            ->groupBy(['variant_id'])
            ->limit(5)
            ->asArray()
            ->orderBy(['count' => SORT_DESC])
            ->all();
        $data = [];
        foreach ($rows as $row) {
            $variant = Variant::findOne($row['variantId']);
            if ($variant) {
                $data[] = [
                    'count' => $row['count'],
                    'model' => $variant
                ];
            }
        }
        return  $data;
    }
}
