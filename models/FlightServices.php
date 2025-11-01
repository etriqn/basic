<?php

namespace app\models;

use yii\db\ActiveRecord;


class FlightServices extends ActiveRecord
{
    public static function tableName()
    {
        return 'flight_services';
    }

    public function getFlight()
    {
        return $this->hasOne(Flights::class, ['flight_id' => 'flight_id']);
    }

    public function getResponsibleTeam()
    {
        return $this->hasOne(Teams::class, ['team_id' => 'responsible_team_id']);
    }
}