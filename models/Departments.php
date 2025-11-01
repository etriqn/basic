<?php

namespace app\models;

use yii\db\ActiveRecord;

class Departments extends ActiveRecord
{
    /*
    public static function tableName()
    {
        return 'departments';
    }*/

    //public function getPositions()
    //{
    //    return $this->hasMany(Positions::class, ['department_id' => 'department_id']);
    //}

    public function getPosition()
    {
        return $this->hasMany(Positions::class, ['department_id' => 'department_id']);
    }

    public function getDepartmentManager()
    {
        return $this->hasOne(DepartmentManagers::class, ['department_id' => 'department_id']);
    }

    public function getTeam()
    {
        return $this->hasMany(Teams::class, ['department_id' => 'department_id']);
    }
}