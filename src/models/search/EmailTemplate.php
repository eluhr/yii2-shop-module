<?php

namespace eluhr\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use eluhr\shop\models\EmailTemplate as EmailTemplateModel;

/**
* EmailTemplate represents the model behind the search form about `eluhr\shop\models\EmailTemplate`.
*/
class EmailTemplate extends EmailTemplateModel
{
    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
[['name', 'template', 'created_at', 'updated_at'], 'safe'],
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
        $query = EmailTemplateModel::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'template', $this->template]);

        return $dataProvider;
    }
}
