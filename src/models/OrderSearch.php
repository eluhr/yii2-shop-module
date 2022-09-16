<?php

namespace eluhr\shop\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 */
class OrderSearch extends Model
{
    public $order;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            'order',
            'safe'
        ];
        return $rules;
    }

    public function formName()
    {
        return '';
    }

    public function search(array $params = []): ActiveDataProvider
    {
        $query = Order::find();
        $query->isOwn();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
