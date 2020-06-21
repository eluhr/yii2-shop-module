<?php

namespace eluhr\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use eluhr\shop\models\OrderItem as OrderItemModel;

/**
 * OrderItem represents the model behind the search form about `eluhr\shop\models\OrderItem`.
 */
class OrderItem extends OrderItemModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'name', 'created_at', 'updated_at'], 'safe'],
            [['variant_id', 'quantity'], 'integer'],
            [['single_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = OrderItemModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'variant_id' => $this->variant_id,
            'quantity' => $this->quantity,
            'single_price' => $this->single_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'order_id', $this->order_id])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
