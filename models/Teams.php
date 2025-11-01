<?php

namespace app\models;

use yii\db\ActiveRecord;


class Teams extends ActiveRecord
{
    public function getDepartment()
    {
        return $this->hasOne(Departments::class, ['department_id' => 'department_id']);
    }

    public function getTeamLeader()
    {
        return $this->hasOne(Employees::class, ['employee_id' => 'team_leader_id']);
    }

    public function getTeamMember()
    {
        return $this->hasMany(TeamMembers::class, ['team_id' => 'team_id']);
    }

    public function getAircraftTeam()
    {
        return $this->hasMany(AircraftTeams::class, ['team_id' => 'team_id']);
    }

    public function getRepair()
    {
        return $this->hasMany(Repairs::class, ['technician_team_id' => 'team_id']);
    }

    public function getFlightService()
    {
        return $this->hasMany(FlightServices::class, ['responsible_team_id' => 'team_id']);
    }
}