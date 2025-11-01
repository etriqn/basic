<?php

namespace app\models;

use yii\db\ActiveRecord;


class Repairs extends ActiveRecord
{
    public function getAircraft()
    {
        return $this->hasOne(Aircrafts::class, ['aircraft_id' => 'aircraft_id']);
    }

    public function getTechnicianTeam()
    {
        return $this->hasOne(Teams::class, ['team_id' => 'technician_team_id']);
    }
}