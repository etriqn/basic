<?php

namespace app\models;

use yii\db\ActiveRecord;


class Routes extends ActiveRecord
{
    public function getFlight()
    {
        return $this->hasMany(Flights::class, ['route_id' => 'route_id']);
    }
}