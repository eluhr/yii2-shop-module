<?php

namespace eluhr\shop\models\search;

use eluhr\shop\models\Order as OrderModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Order represents the model behind the search form about `eluhr\shop\models\Order`.
 */
class Orders extends OrderModel
{
    public $name;
    public $code;

    public const SHIPPING_LINK_FILLED = '0';
    public const SHIPPING_LINK_NOT_FILLED = '1';

    public const INVOICE_NUMBER_FILLED = '0';
    public const INVOICE_NUMBER_NOT_FILLED = '1';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'code', 'status', 'shipment_link', 'type', 'invoice_number'], 'safe'],
            [['id', 'name', 'code', 'status', 'shipment_link', 'type', 'invoice_number'], 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $status)
    {
        $query = OrderModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith('discountCode');

        $query->andFilterWhere(['OR', ['like', 'first_name', $this->name], ['like', 'surname', $this->name]]);
        $query->andFilterWhere(['LIKE', OrderModel::tableName() . '.id', $this->id]);
        $query->andFilterWhere(['LIKE', 'code', $this->code]);

        if ($this->shipment_link === self::SHIPPING_LINK_FILLED) {
            $query->andWhere(['IS NOT', 'shipment_link', null]);
        }
        if ($this->shipment_link === self::SHIPPING_LINK_NOT_FILLED) {
            $query->andWhere(['shipment_link' => null]);
        }

        if ($this->invoice_number === self::INVOICE_NUMBER_FILLED) {
            $query->andWhere(['IS NOT', 'invoice_number', null]);
        }
        if ($this->invoice_number === self::INVOICE_NUMBER_NOT_FILLED) {
            $query->andWhere(['invoice_number' => null]);
        }


        $query->andFilterWhere(['code' => $this->code]);
        if ($status !== \eluhr\shop\models\Order::ALL) {
            $query->andWhere(['status' => $status]);
        } else {
            $query->andFilterWhere(['status' => $this->status]);
        }
        $query->andFilterWhere(['type' => $this->type]);

        $query->orderBy(['created_at' => SORT_DESC]);

        return $dataProvider;
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['name'] = Yii::t('shop', 'Full Name', [], 'de');
        return $attributeLabels;
    }
}
