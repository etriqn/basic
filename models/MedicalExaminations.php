<?php

namespace app\models;

use yii\db\ActiveRecord;


class MedicalExaminations extends ActiveRecord
{
    public static function tableName()
    {
        return 'medical_examinations';
    }
    
    public function getEmployee()
    {
        return $this->hasOne(Employees::class, ['employee_id' => 'employee_id']);
    }
}