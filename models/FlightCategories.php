<?php

namespace app\models;

use yii\db\ActiveRecord;


class FlightCategories extends ActiveRecord
{
    public static function tableName()
    {
        return 'flight_categories';
    }

    public function getFlight()
    {
        return $this->hasMany(Flights::class, ['category_id' => 'category_id']);
    }
}