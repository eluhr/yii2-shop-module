<?php

namespace eluhr\shop\models;

use yii\db\Query;

/**
 * --- PROPERTIES ---
 *
 * @property \eluhr\shop\models\Order[] $orders
 *
 * @author Elias Luhr
 */
class User extends \Da\User\Model\User
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['user_id' => 'id']);
    }

    public function getAddresses(): array
    {
        $fields = [
            'first_name',
            'surname',
            'email',
            'street_name',
            'house_number',
            'postal',
            'city',
            'has_different_delivery_address',
            'delivery_first_name',
            'delivery_surname',
            'delivery_street_name',
            'delivery_house_number',
            'delivery_postal',
            'delivery_city'
        ];
        return (new Query())->select($fields)->from(Order::tableName())->where(['user_id' => $this->id])->groupBy($fields)->all();
    }
}
