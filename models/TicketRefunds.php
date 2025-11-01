<?php

namespace app\models;

use yii\db\ActiveRecord;


class TicketRefunds extends ActiveRecord
{
    public static function tableName()
    {
        return 'ticket_refunds';
    }

    public function getTicket()
    {
        return $this->hasOne(Tickets::class, ['ticket_id' => 'ticket_id']);
    }

    public function getEmployee()
    {
        return $this->hasOne(Employees::class, ['employee_id' => 'employee_id']);
    }
}