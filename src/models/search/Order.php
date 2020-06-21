<?php

namespace eluhr\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use eluhr\shop\models\Order as OrderModel;

/**
 * Order represents the model behind the search form about `eluhr\shop\models\Order`.
 */
class Order extends OrderModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'paypal_id', 'paypal_token', 'paypal_payer_id', 'first_name', 'surname', 'email', 'street_name', 'house_number', 'postal', 'city', 'status', 'created_at', 'updated_at'], 'safe'],
            [['discount_code_id', 'paid'], 'integer'],
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
        $query = OrderModel::find();

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
            'discount_code_id' => $this->discount_code_id,
            'paid' => $this->paid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'paypal_id', $this->paypal_id])
            ->andFilterWhere(['like', 'paypal_token', $this->paypal_token])
            ->andFilterWhere(['like', 'paypal_payer_id', $this->paypal_payer_id])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'street_name', $this->street_name])
            ->andFilterWhere(['like', 'house_number', $this->house_number])
            ->andFilterWhere(['like', 'postal', $this->postal])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
