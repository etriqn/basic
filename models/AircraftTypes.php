<?php

namespace app\models;

use yii\db\ActiveRecord;


class AircraftTypes extends ActiveRecord
{
    public static function tableName()
    {
        return 'aircraft_types';
    }
    
    public function getAircraft()
    {
        return $this->hasMany(Aircrafts::class, ['type_id' => 'type_id']);
    }
}