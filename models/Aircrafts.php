<?php

namespace app\models;

use yii\db\ActiveRecord;


class Aircrafts extends ActiveRecord
{
    public function getAircraftType()
    {
        return $this->hasOne(AircraftTypes::class, ['type_id' => 'type_id']);
    }

    public function getAircraftTeam()
    {
        return $this->hasMany(AircraftTeams::class, ['aircraft_id' => 'aircraft_id']);
    }

    public function getTechnicalInspection()
    {
        return $this->hasMany(TechnicalInspections::class, ['aircraft_id' => 'aircraft_id']);
    }

    public function getRepair()
    {
        return $this->hasMany(Repairs::class, ['aircraft_id' => 'aircraft_id']);
    }

    public function getFlight()
    {
        return $this->hasMany(Flights::class, ['aircraft_id' => 'aircraft_id']);
    }
}