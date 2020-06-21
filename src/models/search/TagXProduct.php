<?php

namespace eluhr\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use eluhr\shop\models\TagXProduct as TagXProductModel;

/**
 * TagXProduct represents the model behind the search form about `eluhr\shop\models\TagXProduct`.
 */
class TagXProduct extends TagXProductModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'product_id'], 'integer'],
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
        $query = TagXProductModel::find();

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
            'tag_id' => $this->tag_id,
            'product_id' => $this->product_id,
        ]);

        return $dataProvider;
    }
}
