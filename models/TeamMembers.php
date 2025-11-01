<?php

namespace app\models;

use yii\db\ActiveRecord;


class TeamMembers extends ActiveRecord
{
    public static function tableName()
    {
        return 'team_members';
    }
    
    public function getTeam()
    {
        return $this->hasOne(Teams::class, ['team_id' => 'team_id']);
    }

    public function getEmployee()
    {
        return $this->hasOne(Employees::class, ['employee_id' => 'employee_id']);
    }
}