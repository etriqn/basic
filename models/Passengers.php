<?php

namespace app\models;

use yii\db\ActiveRecord;


class Passengers extends ActiveRecord
{
    public function getTicket()
    {
        return $this->hasMany(Tickets::class, ['passenger_id' => 'passenger_id']);
    }
}