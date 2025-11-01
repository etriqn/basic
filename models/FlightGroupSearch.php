<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class FlightGroupSearch extends Model
{
    
    public $route_id;
    public $departure_airport;
    public $arrival_airport;
    public $min_duration;
    public $max_duration;
    public $min_price;
    public $max_price;
    public $flight_status;
    public $delay_reason;
    public $aircraft_type_id;
    public $category_id;
    public $date_from;
    public $date_to;
    
    // Для навигации по вкладкам
    public $activeTab = 'flights';

    public function rules()
    {
        return [
            // Общие правила
            [['route_id', 'min_duration', 'max_duration', 'aircraft_type_id', 'category_id'], 'integer'],
            [['min_price', 'max_price'], 'number', 'min' => 0],
            [['departure_airport', 'arrival_airport'], 'string', 'max' => 100],
            [['flight_status'], 'in', 'range' => ['scheduled', 'boarding', 'departed', 'arrived', 'delayed', 'cancelled', 'all']],
            [['delay_reason'], 'in', 'range' => ['weather', 'technical', 'crew', 'air_traffic', 'other', 'all']],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
            
            // Валидация диапазонов
            ['max_duration', 'compare', 'compareAttribute' => 'min_duration', 'operator' => '>='],
            ['max_price', 'compare', 'compareAttribute' => 'min_price', 'operator' => '>='],
        ];
    }

    public function attributeLabels()
    {
        return [
            'route_id' => 'Маршрут',
            'departure_airport' => 'Аэропорт вылета',
            'arrival_airport' => 'Аэропорт прилета',
            'min_duration' => 'Минимальная длительность (мин)',
            'max_duration' => 'Максимальная длительность (мин)',
            'min_price' => 'Минимальная цена билета',
            'max_price' => 'Максимальная цена билета',
            'flight_status' => 'Статус рейса',
            'delay_reason' => 'Причина задержки',
            'aircraft_type_id' => 'Тип самолета',
            'category_id' => 'Категория рейса',
            'date_from' => 'Дата с',
            'date_to' => 'Дата по',
        ];
    }

    public function getRoutesList()
    {
        return ArrayHelper::map(Routes::find()->all(), 'route_id', function($model) {
            return $model->departure_airport . ' → ' . $model->arrival_airport . 
                   ($model->transit_airport ? ' (через ' . $model->transit_airport . ')' : '');
        });
    }

    public function getAircraftTypesList()
    {
        return ArrayHelper::map(AircraftTypes::find()->all(), 'type_id', 'type_name');
    }

    public function getFlightCategoriesList()
    {
        return ArrayHelper::map(FlightCategories::find()->all(), 'category_id', 'category_name');
    }

    public function getFlightStatusList()
    {
        return [
            'all' => 'Все статусы',
            'scheduled' => 'Запланирован',
            'boarding' => 'Посадка',
            'departed' => 'Вылетел',
            'arrived' => 'Прибыл',
            'delayed' => 'Задержан',
            'cancelled' => 'Отменен',
        ];
    }

    public function getDelayReasonList()
    {
        return [
            'all' => 'Все причины',
            'weather' => 'Погода',
            'technical' => 'Технические',
            'crew' => 'Экипаж',
            'air_traffic' => 'Воздушное движение',
            'other' => 'Другие',
        ];
    }

    //Запрос 6: Рейсы по маршруту, длительности, цене
    public function searchFlights()
    {
        $query = Flights::find()
            ->joinWith([
                'route', 
                'aircraft.aircraftType', 
                'flightCategory',
                'ticket' => function($query) {
                    $query->andWhere(['tickets.status' => ['booked', 'confirmed']]);
                }
            ])
            ->select([
                'flights.*',
                'COUNT(tickets.ticket_id) as tickets_sold'
            ])
            ->groupBy('flights.flight_id');

        // Фильтр по маршруту
        if (!empty($this->route_id)) {
            $query->andWhere(['flights.route_id' => $this->route_id]);
        }

        // Фильтр по аэропорту вылета
        if (!empty($this->departure_airport)) {
            $query->andWhere(['like', 'routes.departure_airport', $this->departure_airport]);
        }

        // Фильтр по аэропорту прилета
        if (!empty($this->arrival_airport)) {
            $query->andWhere(['like', 'routes.arrival_airport', $this->arrival_airport]);
        }

        // Фильтр по длительности
        if (!empty($this->min_duration)) {
            $query->andWhere(['>=', 'routes.estimated_duration_min', $this->min_duration]);
        }
        if (!empty($this->max_duration)) {
            $query->andWhere(['<=', 'routes.estimated_duration_min', $this->max_duration]);
        }

        // Фильтр по цене билета
        if (!empty($this->min_price)) {
            $query->andWhere(['>=', 'flights.base_ticket_price', $this->min_price]);
        }
        if (!empty($this->max_price)) {
            $query->andWhere(['<=', 'flights.base_ticket_price', $this->max_price]);
        }

        // Фильтр по статусу рейса
        if ($this->flight_status !== 'all') {
            $query->andWhere(['flights.status' => $this->flight_status]);
        }

        return $this->createDataProvider($query);
    }

    //Запрос 7: Отмененные рейсы
    public function searchCancelledFlights()
    {
        $query = Flights::find()
            ->joinWith([
                'route', 
                'aircraft.aircraftType',
                'ticket' => function($query) {
                    $query->andWhere(['tickets.status' => ['booked', 'confirmed']]);
                }
            ])
            ->select([
                'flights.*',
                'COUNT(tickets.ticket_id) as tickets_sold',
                'aircraft_types.capacity as aircraft_capacity',
                '(aircraft_types.capacity - COUNT(tickets.ticket_id)) as unused_seats', // v2
                'ROUND(((aircraft_types.capacity - COUNT(tickets.ticket_id)) / aircraft_types.capacity * 100), 2) as unused_percentage'// v2
            ])
            ->where(['flights.status' => 'cancelled'])
            ->groupBy('flights.flight_id');

        // Фильтр по маршруту
        if (!empty($this->route_id)) {
            $query->andWhere(['flights.route_id' => $this->route_id]);
        }

        // Фильтр по аэропорту вылета
        if (!empty($this->departure_airport)) {
            $query->andWhere(['like', 'routes.departure_airport', $this->departure_airport]);
        }

        // Фильтр по аэропорту прилета
        if (!empty($this->arrival_airport)) {
            $query->andWhere(['like', 'routes.arrival_airport', $this->arrival_airport]);
        }

        return $this->createDataProvider($query);
    }

    //Запрос 8: Задержанные рейсы
    public function searchDelayedFlights()
    {
        $query = Flights::find()
            ->joinWith([
                'route', 
                'aircraft.aircraftType',
                'flightDelay',
                'ticket.ticketRefund'
            ])
            ->select([
                'flights.*',
                'COUNT(DISTINCT ticket_refunds.refund_id) as refunds_count'
            ])
            ->where(['flights.status' => 'delayed'])
            ->groupBy('flights.flight_id');

        // Фильтр по причине задержки
        if ($this->delay_reason !== 'all') {
            $query->andWhere(['flight_delays.delay_reason' => $this->delay_reason]);
        }

        // Фильтр по маршруту
        if (!empty($this->route_id)) {
            $query->andWhere(['flights.route_id' => $this->route_id]);
        }

        // Фильтр по периоду
        if (!empty($this->date_from)) {
            $query->andWhere(['>=', 'flights.departure_time', $this->date_from . ' 00:00:00']);
        }
        if (!empty($this->date_to)) {
            $query->andWhere(['<=', 'flights.departure_time', $this->date_to . ' 23:59:59']);
        }

        return $this->createDataProvider($query);
    }

    //Запрос 9: Рейсы по типу самолета и статистика билетов
    public function searchFlightsByAircraft()
    {
        $query = Flights::find()
            ->joinWith([
                'route', 
                'aircraft.aircraftType',
                'ticket' => function($query) {
                    $query->andWhere(['tickets.status' => ['booked', 'confirmed']]);
                }
            ])
            ->select([
                'flights.*',
                'COUNT(tickets.ticket_id) as tickets_sold',
                'AVG(tickets.ticket_price) as avg_ticket_price'
            ])
            ->groupBy('flights.flight_id');

        // Фильтр по типу самолета
        if (!empty($this->aircraft_type_id)) {
            $query->andWhere(['aircrafts.type_id' => $this->aircraft_type_id]);
        }

        // Фильтр по длительности
        if (!empty($this->min_duration)) {
            $query->andWhere(['>=', 'routes.estimated_duration_min', $this->min_duration]);
        }
        if (!empty($this->max_duration)) {
            $query->andWhere(['<=', 'routes.estimated_duration_min', $this->max_duration]);
        }

        // Фильтр по цене билета
        if (!empty($this->min_price)) {
            $query->andWhere(['>=', 'flights.base_ticket_price', $this->min_price]);
        }
        if (!empty($this->max_price)) {
            $query->andWhere(['<=', 'flights.base_ticket_price', $this->max_price]);
        }

        // Фильтр по периоду
        if (!empty($this->date_from)) {
            $query->andWhere(['>=', 'flights.departure_time', $this->date_from . ' 00:00:00']);
        }
        if (!empty($this->date_to)) {
            $query->andWhere(['<=', 'flights.departure_time', $this->date_to . ' 23:59:59']);
        }

        return $this->createDataProvider($query);
    }

    //Запрос 10: Рейсы по категории и направлению
    public function searchFlightsByCategory()
    {
        $query = Flights::find()
            ->joinWith([
                'route', 
                'aircraft.aircraftType',
                'flightCategory'
            ])
            ->groupBy('flights.flight_id');

        // Фильтр по категории рейса
        if (!empty($this->category_id)) {
            $query->andWhere(['flights.category_id' => $this->category_id]);
        }

        // Фильтр по типу самолета
        if (!empty($this->aircraft_type_id)) {
            $query->andWhere(['aircrafts.type_id' => $this->aircraft_type_id]);
        }

        // Фильтр по аэропорту вылета
        if (!empty($this->departure_airport)) {
            $query->andWhere(['like', 'routes.departure_airport', $this->departure_airport]);
        }

        // Фильтр по аэропорту прилета
        if (!empty($this->arrival_airport)) {
            $query->andWhere(['like', 'routes.arrival_airport', $this->arrival_airport]);
        }

        return $this->createDataProvider($query);
    }

    
    private function createDataProvider($query)
    {
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['departure_time' => SORT_DESC],
                'attributes' => [
                    'flight_number',
                    'departure_time',
                    'arrival_time',
                    'base_ticket_price',
                    'status',
                    'routes.departure_airport' => [
                        'asc' => ['routes.departure_airport' => SORT_ASC],
                        'desc' => ['routes.departure_airport' => SORT_DESC],
                    ],
                    'routes.arrival_airport' => [
                        'asc' => ['routes.arrival_airport' => SORT_ASC],
                        'desc' => ['routess.arrival_airport' => SORT_DESC],
                    ],
                    'tickets_sold' => [
                        'asc' => ['COUNT(tickets.ticket_id)' => SORT_ASC],
                        'desc' => ['COUNT(tickets.ticket_id)' => SORT_DESC],
                    ],
                ],
            ],
        ]);
    }
}