<?php

namespace eluhr\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use eluhr\shop\models\DiscountCode as DiscountCodeModel;

/**
 * DiscountCode represents the model behind the search form about `eluhr\shop\models\DiscountCode`.
 */
class DiscountCode extends DiscountCodeModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'used','type'], 'integer'],
            [['code', 'expiration_date', 'created_at', 'updated_at'], 'safe'],
            [['value'], 'number'],
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
        $query = DiscountCodeModel::find();

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
            'id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
            'expiration_date' => $this->expiration_date,
            'used' => $this->used,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
