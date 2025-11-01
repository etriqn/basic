<?php

namespace app\models;

use yii\db\ActiveRecord;

class Employees extends ActiveRecord
{
    /*
    public static function tableName()
    {
        return 'employees';
    }*/
    
    public function getPosition()
    {
        return $this->hasOne(Positions::class, ['position_id' => 'position_id']);
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required'],
            ['email', 'email'],
            ['salary', 'number', 'min' => 0],
        ];
    }

    public function getDepartmentManager()
    {
        return $this->hasOne(DepartmentManagers::class, ['employee_id' => 'employee_id']);
    }

    public function getTeamMember()
    {
        return $this->hasMany(TeamMembers::class, ['employee_id' => 'employee_id']);
    }
    
    public function getMedicalExamination()
    {
        return $this->hasMany(MedicalExaminations::class, ['employee_id' => 'employee_id']);
    }

    public function getTechnicalInspection()
    {
        return $this->hasMany(TechnicalInspections::class, ['inspector_id' => 'employee_id']);
    }

    public function getTicketRefund()
    {
        return $this->hasMany(TicketRefunds::class, ['processed_by_employee_id' => 'employee_id']);
    }
}