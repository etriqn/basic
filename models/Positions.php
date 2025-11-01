<?php

namespace app\models;

use yii\db\ActiveRecord;

class Positions extends ActiveRecord
{
    /*
    public static function tableName()
    {
        return 'positions';
    }*/

    public function getDepartment()
    {
        return $this->hasOne(Departments::class, ['department_id' => 'department_id']);
    }

    public function getEmployee()
    {
        return $this->hasMany(Employees::class, ['position_id' => 'position_id']);
    }
}