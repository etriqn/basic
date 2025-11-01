<?php

namespace app\models;

use yii\db\ActiveRecord;


class TechnicalInspections extends ActiveRecord
{
    public static function tableName()
    {
        return 'technical_inspections';
    }

    public function getAircraft()
    {
        return $this->hasOne(Aircrafts::class, ['aircraft_id' => 'aircraft_id']);
    }

    public function getInspector()
    {
        return $this->hasOne(Employees::class, ['employee_id' => 'inspector_id']);
    }
}