<?php

namespace app\models;

use yii\db\ActiveRecord;


class AircraftTeams extends ActiveRecord
{
    public static function tableName()
    {
        return 'aircraft_teams';
    }

    public function getAircraft()
    {
        return $this->hasOne(Aircrafts::class, ['aircraft_id' => 'aircraft_id']);
    }

    public function getTeam()
    {
        return $this->hasOne(Teams::class, ['team_id' => 'team_id']);
    }
}