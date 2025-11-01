<?php

namespace app\models;

use yii\db\ActiveRecord;


class Flights extends ActiveRecord
{
    public function getRoute()
    {
        return $this->hasOne(Routes::class, ['route_id' => 'route_id']);
    }

    public function getAircraft()
    {
        return $this->hasOne(Aircrafts::class, ['aircraft_id' => 'aircraft_id']);
    }

    public function getFlightCategory()
    {
        return $this->hasOne(FlightCategories::class, ['category_id' => 'category_id']);
    }

    public function getFlightDelay()
    {
        return $this->hasMany(FlightDelays::class, ['flight_id' => 'flight_id']);
    }

    public function getTicket()
    {
        return $this->hasMany(Tickets::class, ['flight_id' => 'flight_id']);
    }

    public function getFlightService()
    {
        return $this->hasMany(FlightServices::class, ['flight_id' => 'flight_id']);
    }
}