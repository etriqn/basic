<?php

namespace app\models;

use yii\db\ActiveRecord;


class Tickets extends ActiveRecord
{
    public function getFlight()
    {
        return $this->hasOne(Flights::class, ['flight_id' => 'flight_id']);
    }

    public function getPassenger()
    {
        return $this->hasOne(Passengers::class, ['passenger_id' => 'passenger_id']);
    }

    public function getTicketRefund()
    {
        return $this->hasMany(TicketRefunds::class, ['ticket_id' => 'ticket_id']);
    }
}