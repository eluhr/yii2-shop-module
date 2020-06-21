<?php

namespace eluhr\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use eluhr\shop\models\Variant as VariantModel;

/**
 * Variant represents the model behind the search form about `eluhr\shop\models\Variant`.
 */
class Variant extends VariantModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'is_online', 'rank', 'stock'], 'integer'],
            [['title', 'thumbnail_image', 'hex_color', 'created_at', 'updated_at'], 'safe'],
            [['price'], 'number'],
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
        $query = VariantModel::find();

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
            'product_id' => $this->product_id,
            'is_online' => $this->is_online,
            'rank' => $this->rank,
            'price' => $this->price,
            'stock' => $this->stock,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'thumbnail_image', $this->thumbnail_image])
            ->andFilterWhere(['like', 'hex_color', $this->hex_color]);

        return $dataProvider;
    }
}
