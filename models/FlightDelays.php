<?php

namespace app\models;

use yii\db\ActiveRecord;


class FlightDelays extends ActiveRecord
{
    public static function tableName()
    {
        return 'flight_delays';
    }
    
    public function getFlight()
    {
        return $this->hasOne(Flights::class, ['flight_id' => 'flight_id']);
    }
}