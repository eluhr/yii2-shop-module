<?php

namespace eluhr\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use eluhr\shop\models\Setting as SettingModel;

/**
* Setting represents the model behind the search form about `eluhr\shop\models\Setting`.
*/
class Setting extends SettingModel
{
    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
[['key', 'value'], 'safe'],
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
        $query = SettingModel::find();

        $dataProvider = new ActiveDataProvider([
'query' => $query,
]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
