<?php

namespace app\models;

use yii\db\ActiveRecord;

class DepartmentManagers extends ActiveRecord
{
    public static function tableName()
    {
        return 'department_managers';
    }

    public function getEmployee()
    {
        return $this->hasOne(Employees::class, ['employee_id' => 'employee_id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(Departments::class, ['department_id' => 'department_id']);
    }
}