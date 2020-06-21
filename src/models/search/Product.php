<?php

namespace eluhr\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use eluhr\shop\models\Product as ProductModel;

/**
 * Product represents the model behind the search form about `eluhr\shop\models\Product`.
 */
class Product extends ProductModel
{
    public $tagsFilter;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_online', 'rank'], 'integer'],
            [['title', 'description', 'created_at', 'updated_at','tagsFilter'], 'safe'],
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
        $query = ProductModel::find();

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
            'is_online' => $this->is_online,
            'rank' => $this->rank,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        if (!empty($this->tagsFilter)) {
            $query->joinWith('tags');
            $query->andFilterWhere(['like',\eluhr\shop\models\Tag::tableName().'.name', $this->tagsFilter]);
        }

        return $dataProvider;
    }
}
