<?php

namespace eluhr\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use eluhr\shop\models\TagXFilter as TagXFilterModel;

/**
 * TagXFilter represents the model behind the search form about `eluhr\shop\models\TagXFilter`.
 */
class TagXFilter extends TagXFilterModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'facet_id', 'show_in_frontend'], 'integer'],
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
        $query = TagXFilterModel::find();

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
            'facet_id' => $this->facet_id,
            'show_in_frontend' => $this->show_in_frontend,
        ]);

        return $dataProvider;
    }
}
