<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class AircraftGroupSearch extends Model
{
    
    public $aircraft_status;
    public $date_from;
    public $date_to;
    public $min_flights;
    public $max_flights;
    public $aircraft_age;
    public $repair_count;
    public $inspection_type;
    public $inspection_result;
    
    // Для навигации по вкладкам
    public $activeTab = 'list';

    public function rules()
    {
        return [
            // Общие правила
            [['min_flights', 'max_flights', 'aircraft_age', 'repair_count'], 'default', 'value' => null],
            [['min_flights', 'max_flights', 'aircraft_age', 'repair_count'], 'integer', 'min' => 0],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
            [['aircraft_status'], 'in', 'range' => ['active', 'maintenance', 'retired', 'all']],
            [['inspection_type'], 'in', 'range' => ['routine', 'preflight', 'special', 'all']],
            [['inspection_result'], 'in', 'range' => ['passed', 'failed', 'requires_repair', 'all']],

            // Валидация диапазонов
            ['max_flights', 'compare', 'compareAttribute' => 'min_flights', 'operator' => '>=', 'skipOnEmpty' => true],

            // Условные обязательные поля
            [['date_from', 'date_to'], 'required', 'when' => function($model) {
                return in_array($model->activeTab, ['inspections', 'repairs']);
            }, 'message' => 'Поле обязательно для этого типа поиска'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'aircraft_status' => 'Статус самолета',
            'date_from' => 'Дата с',
            'date_to' => 'Дата по',
            'min_flights' => 'Минимальное количество рейсов',
            'max_flights' => 'Максимальное количество рейсов',
            'aircraft_age' => 'Возраст самолета (лет)',
            'repair_count' => 'Количество ремонтов',
            'inspection_type' => 'Тип техосмотра',
            'inspection_result' => 'Результат техосмотра',
        ];
    }

    public function getAircraftStatusList()
    {
        return [
            'all' => 'Все статусы',
            'active' => 'Активный',
            'maintenance' => 'На обслуживании', 
            'retired' => 'Списанный',
        ];
    }

    public function getInspectionTypeList()
    {
        return [
            'all' => 'Все типы',
            'routine' => 'Плановый',
            'preflight' => 'Предполетный',
            'special' => 'Специальный',
        ];
    }

    public function getInspectionResultList()
    {
        return [
            'all' => 'Все результаты',
            'passed' => 'Пройден',
            'failed' => 'Не пройден',
            'requires_repair' => 'Требует ремонта',
        ];
    }

    //Запрос 4: Список самолетов приписанных к аэропорту

    public function searchAircraftList()
    {
        $query = Aircrafts::find()
            ->joinWith([
                'aircraftType',
                'flight'
            ])
            ->select([
                'aircrafts.*',
                'COUNT(DISTINCT flights.flight_id) as flight_count'
            ])
            ->groupBy('aircrafts.aircraft_id');

        // Фильтр по статусу самолета
        if ($this->aircraft_status !== 'all') {
            $query->andWhere(['aircrafts.status' => $this->aircraft_status]);
        }

        // Фильтр по количеству рейсов
        if ($this->min_flights !== null && $this->min_flights !== '') {
            $query->andHaving(['>=', 'COUNT(flights.flight_id)', (int)$this->min_flights]);
        }
        if ($this->max_flights !== null && $this->max_flights !== '') {
            $query->andHaving(['<=', 'COUNT(flights.flight_id)', (int)$this->max_flights]);
        }

        // Фильтр по возрасту самолета
        if ($this->aircraft_age !== null && $this->aircraft_age !== '') {
            $currentYear = date('Y');
            $targetYear = $currentYear - (int)$this->aircraft_age;
            $query->andWhere(['<=', 'YEAR(aircrafts.manufacture_date)', $targetYear]);
        }

        return $this->createDataProvider($query);
    }

    //Запрос 5: Самолеты, прошедшие техосмотр за период

    public function searchTechInspections()
    {
        // Сначала ищем ID самолетов, которые прошли техосмотр в указанный период
        $subQuery = TechnicalInspections::find()
            ->select(['aircraft_id'])
            ->where(['result' => 'passed'])
            ->andWhere(['>=', 'inspection_date', $this->date_from . ' 00:00:00'])
            ->andWhere(['<=', 'inspection_date', $this->date_to . ' 23:59:59'])
            ->groupBy('aircraft_id');

        if ($this->inspection_type !== 'all') {
            $subQuery->andWhere(['inspection_type' => $this->inspection_type]);
        }

        $query = Aircrafts::find()
            ->joinWith([
                'aircraftType',
                'technicalInspection' => function($query) {
                    $query->andWhere(['>=', 'technical_inspections.inspection_date', $this->date_from . ' 00:00:00'])
                          ->andWhere(['<=', 'technical_inspections.inspection_date', $this->date_to . ' 23:59:59'])
                          ->andWhere(['technical_inspections.result' => 'passed']);

                    if ($this->inspection_type !== 'all') {
                        $query->andWhere(['technical_inspections.inspection_type' => $this->inspection_type]);
                    }
                    if ($this->inspection_result !== 'all') {
                        $query->andWhere(['technical_inspections.result' => $this->inspection_result]);
                    }
                }
            ])
            ->where(['in', 'aircrafts.aircraft_id', $subQuery])
            ->groupBy('aircrafts.aircraft_id');

        // Фильтр по статусу самолета
        if ($this->aircraft_status !== 'all') {
            $query->andWhere(['aircrafts.status' => $this->aircraft_status]);
        }

        return $this->createDataProvider($query);
    }

    //Запрос 5: Самолеты, отправленные в ремонт

    public function searchRepairs()
    {
        $subQuery = Repairs::find()
            ->select(['aircraft_id'])
            ->where(['>=', 'start_date', $this->date_from])
            ->andWhere(['<=', 'start_date', $this->date_to])
            ->groupBy('aircraft_id');
    
        if ($this->repair_count !== null) {
            $subQuery->having(['>=', 'COUNT(repair_id)', $this->repair_count]);
        }
    
        $query = Aircrafts::find()
            ->joinWith([
                'aircraftType',
                'repair' => function($query) {
                    $query->andWhere(['>=', 'repairs.start_date', $this->date_from])
                          ->andWhere(['<=', 'repairs.start_date', $this->date_to]);
                }
            ])
            ->where(['in', 'aircrafts.aircraft_id', $subQuery])
            ->groupBy('aircrafts.aircraft_id');
            
        // Фильтр по статусу самолета
        if ($this->aircraft_status !== 'all') {
            $query->andWhere(['aircrafts.status' => $this->aircraft_status]);
        }
    
        return $this->createDataProvider($query);
    }

    //Создание DataProvider с общими настройками

    private function createDataProvider($query)
    {
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['registration_number' => SORT_ASC],
                'attributes' => [
                    'registration_number',
                    'manufacture_date',
                    'acquisition_date',
                    'status',
                    'aircraftType.type_name' => [
                        'asc' => ['aircraft_types.type_name' => SORT_ASC],
                        'desc' => ['aircraft_types.type_name' => SORT_DESC],
                    ],
                    'aircraftType.manufacturer' => [
                        'asc' => ['aircraft_types.manufacturer' => SORT_ASC],
                        'desc' => ['aircraft_types.manufacturer' => SORT_DESC],
                    ],
                    'flight_count' => [
                        'asc' => ['COUNT(flights.flight_id)' => SORT_ASC],
                        'desc' => ['COUNT(flights.flight_id)' => SORT_DESC],
                    ],
                ],
            ],
        ]);
    }
}