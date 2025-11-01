<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class PassengerTicketSearch extends Model
{
    
    public $flight_id;
    public $departure_date;
    public $route_id;
    public $min_price;
    public $max_price;
    public $gender;
    public $min_age;
    public $max_age;
    public $has_baggage;
    public $ticket_status;
    
    // Для навигации по вкладкам
    public $activeTab = 'passengers';

    public function rules()
    {
        return [
            // Общие правила
            [['flight_id', 'route_id', 'min_age', 'max_age'], 'integer'],
            [['min_price', 'max_price'], 'number', 'min' => 0],
            [['departure_date'], 'date', 'format' => 'php:Y-m-d'],
            [['gender'], 'in', 'range' => ['M', 'F', 'all']],
            [['has_baggage'], 'in', 'range' => ['1', '0', 'all']],
            [['ticket_status'], 'in', 'range' => ['booked', 'confirmed', 'cancelled', 'refunded', 'all']],
            
            // Валидация диапазонов
            ['max_age', 'compare', 'compareAttribute' => 'min_age', 'operator' => '>='],
            ['max_price', 'compare', 'compareAttribute' => 'min_price', 'operator' => '>='],
        ];
    }

    public function attributeLabels()
    {
        return [
            'flight_id' => 'Рейс',
            'departure_date' => 'Дата вылета',
            'route_id' => 'Маршрут',
            'min_price' => 'Минимальная цена',
            'max_price' => 'Максимальная цена',
            'gender' => 'Пол',
            'min_age' => 'Минимальный возраст',
            'max_age' => 'Максимальный возраст',
            'has_baggage' => 'Наличие багажа',
            'ticket_status' => 'Статус билета',
        ];
    }

    //public function getFlightsList()
    //{
    //    return ArrayHelper::map(Flights::find()
    //        ->joinWith('route')
    //        ->select(['flights.flight_id', 'flights.flight_number', 'routes.departure_airport', 'routes.arrival_airport'])
    //        ->all(), 'flight_id', function($model) {
    //            return $model->flight_number . ' (' . $model->route->departure_airport . ' → ' . $model->route->arrival_airport . ')';
    //        });
    //}

    //public function getFlightsList()
    //{
    //    return ArrayHelper::map(Flights::find()
    //        ->joinWith('route')
    //        ->select(['flights.flight_id', 'flights.flight_number', 'routes.departure_airport', 'routes.arrival_airport'])
    //        ->all(), 'flight_id', function($model) {
    //            // Добавляем проверку на существование маршрута
    //            if ($model->route) {
    //                return $model->flight_number . ' (' . $model->route->departure_airport . ' → ' . $model->route->arrival_airport . ')';
    //            } else {
    //                return $model->flight_number . ' (Маршрут не указан)';
    //            }
    //        });
    //}

    public function getFlightsList()
    {
        $flights = Flights::find()
            ->joinWith('route')
            ->select(['flights.flight_id', 'flights.flight_number', 'routes.departure_airport', 'routes.arrival_airport'])
            ->all();

        $result = [];
        foreach ($flights as $flight) {
            if ($flight->route) {
                $result[$flight->flight_id] = $flight->flight_number . ' (' . $flight->route->departure_airport . ' → ' . $flight->route->arrival_airport . ')';
            } else {
                $result[$flight->flight_id] = $flight->flight_number . ' (Маршрут не указан)';
            }
        }

        return $result;
    }

    public function getRoutesList()
    {
        return ArrayHelper::map(Routes::find()->all(), 'route_id', function($model) {
            return $model->departure_airport . ' → ' . $model->arrival_airport;
        });
    }

    public function getGenderList()
    {
        return [
            'all' => 'Любой пол',
            'M' => 'Мужской',
            'F' => 'Женский',
        ];
    }

    public function getHasBaggageList()
    {
        return [
            'all' => 'Не важно',
            '1' => 'С багажом',
            '0' => 'Без багажа',
        ];
    }

    public function getTicketStatusList()
    {
        return [
            'all' => 'Все статусы',
            'booked' => 'Забронирован',
            'confirmed' => 'Подтвержден',
            'cancelled' => 'Отменен',
            'refunded' => 'Возвращен',
        ];
    }

    //Запрос 11: Пассажиры на рейсе
    public function searchPassengers()
    {
        $query = Passengers::find()
            ->joinWith([
                'ticket.flight.route',
                'ticket' => function($query) {
                    $query->andWhere(['tickets.status' => ['booked', 'confirmed']]);
                }
            ])
            ->select([
                'passengers.*',
                'COUNT(tickets.ticket_id) as tickets_count'
            ])
            ->groupBy('passengers.passenger_id');

        // Фильтр по рейсу
        if (!empty($this->flight_id)) {
            $query->andWhere(['tickets.flight_id' => $this->flight_id]);
        }

        // Фильтр по дате вылета
        if (!empty($this->departure_date)) {
            $query->andWhere(['DATE(flights.departure_time)' => $this->departure_date]);
        }

        // Фильтр по маршруту
        if (!empty($this->route_id)) {
            $query->andWhere(['flights.route_id' => $this->route_id]);
        }

        // Фильтр по наличию багажа
        if ($this->has_baggage !== 'all') {
            $query->andWhere(['tickets.has_baggage' => $this->has_baggage]);
        }

        // Фильтр по полу
        if ($this->gender !== 'all') {
            $query->andWhere(['passengers.gender' => $this->gender]);
        }

        // Фильтр по возрасту
        if (!empty($this->min_age)) {
            $minBirthDate = date('Y-m-d', strtotime('-' . $this->min_age . ' years'));
            $query->andWhere(['<=', 'passengers.birth_date', $minBirthDate]);
        }
        if (!empty($this->max_age)) {
            $maxBirthDate = date('Y-m-d', strtotime('-' . $this->max_age . ' years'));
            $query->andWhere(['>=', 'passengers.birth_date', $maxBirthDate]);
        }

        return $this->createDataProvider($query);
    }

    //Запрос 12: Свободные и забронированные места

    public function searchSeats()
    {
        $query = Tickets::find()
            ->joinWith([
                'flight.route',
                'flight.aircraft.aircraftType',
                'passenger'
            ])
            ->select([
                'tickets.*',
                'flights.departure_time',
                'flights.arrival_time',
                'flights.base_ticket_price',
                'routes.departure_airport',
                'routes.arrival_airport'
            ]);

        // Фильтр по рейсу
        if (!empty($this->flight_id)) {
            $query->andWhere(['tickets.flight_id' => $this->flight_id]);
        }

        // Фильтр по дате вылета
        if (!empty($this->departure_date)) {
            $query->andWhere(['DATE(flights.departure_time)' => $this->departure_date]);
        }

        // Фильтр по маршруту
        if (!empty($this->route_id)) {
            $query->andWhere(['flights.route_id' => $this->route_id]);
        }

        // Фильтр по цене билета
        if (!empty($this->min_price)) {
            $query->andWhere(['>=', 'tickets.ticket_price', $this->min_price]);
        }
        if (!empty($this->max_price)) {
            $query->andWhere(['<=', 'tickets.ticket_price', $this->max_price]);
        }

        // Фильтр по статусу билета
        if ($this->ticket_status !== 'all') {
            $query->andWhere(['tickets.status' => $this->ticket_status]);
        }

        return $this->createDataProvider($query);
    }

    //Запрос 13: Сданные билеты
    public function searchRefundedTickets()
    {
        $query = TicketRefunds::find()
            ->joinWith([
                'ticket.flight.route',
                'ticket.passenger',
                'employee'
            ])
            ->select([
                'ticket_refunds.*',
                'tickets.ticket_price',
                'tickets.seat_number',
                'flights.departure_time',
                'routes.departure_airport',
                'routes.arrival_airport'
            ])
            ->where(['tickets.status' => 'refunded']);

        // Фильтр по рейсу
        if (!empty($this->flight_id)) {
            $query->andWhere(['tickets.flight_id' => $this->flight_id]);
        }

        // Фильтр по дате вылета
        if (!empty($this->departure_date)) {
            $query->andWhere(['DATE(flights.departure_time)' => $this->departure_date]);
        }

        // Фильтр по маршруту
        if (!empty($this->route_id)) {
            $query->andWhere(['flights.route_id' => $this->route_id]);
        }

        // Фильтр по цене билета
        if (!empty($this->min_price)) {
            $query->andWhere(['>=', 'tickets.ticket_price', $this->min_price]);
        }
        if (!empty($this->max_price)) {
            $query->andWhere(['<=', 'tickets.ticket_price', $this->max_price]);
        }

        // Фильтр по полу пассажира
        if ($this->gender !== 'all') {
            $query->andWhere(['passengers.gender' => $this->gender]);
        }

        // Фильтр по возрасту пассажира
        if (!empty($this->min_age)) {
            $minBirthDate = date('Y-m-d', strtotime('-' . $this->min_age . ' years'));
            $query->andWhere(['<=', 'passengers.birth_date', $minBirthDate]);
        }
        if (!empty($this->max_age)) {
            $maxBirthDate = date('Y-m-d', strtotime('-' . $this->max_age . ' years'));
            $query->andWhere(['>=', 'passengers.birth_date', $maxBirthDate]);
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
            
        ]);
    }
}