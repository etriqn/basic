<?php

use yii\helpers\Html;
use yii\grid\GridView;

$titles = [
    'passengers' => 'Результаты поиска пассажиров',
    'seats' => 'Результаты поиска мест на рейсах', 
    'refunds' => 'Результаты поиска сданных билетов',
];

$this->title = $titles[$activeTab] ?? 'Результаты поиска';
$this->params['breadcrumbs'][] = ['label' => 'Поиск пассажиров и билетов', 'url' => ['search', 'tab' => $activeTab]];
$this->params['breadcrumbs'][] = $this->title;

// Базовые колонки для всех вкладок
$columns = [];

// Колонки для вкладки "Пассажиры на рейсах"
if ($activeTab === 'passengers') {
    $columns = [
        [
            'attribute' => 'first_name',
            'label' => 'Имя',
        ],
        [
            'attribute' => 'last_name',
            'label' => 'Фамилия',
        ],
        [
            'attribute' => 'birth_date',
            'value' => function($model) {
                $birthDate = new \DateTime($model->birth_date);
                $today = new \DateTime();
                $age = $today->diff($birthDate)->y;
                return $model->birth_date . ' (' . $age . ' лет)';
            },
            'label' => 'Дата рождения (Возраст)',
        ],
        [
            'attribute' => 'gender',
            'value' => function($model) {
                return $model->gender == 'M' ? 'Мужской' : 'Женский';
            },
            'label' => 'Пол',
        ],
        [
            'attribute' => 'passport_number',
            'label' => 'Номер паспорта',
        ],
        [
            'label' => 'Рейсы',
            'value' => function($model) {
                $flights = [];
                foreach ($model->ticket as $ticket) {
                    if (in_array($ticket->status, ['booked', 'confirmed'])) {
                        $flight = $ticket->flight;
                        $flights[] = $flight->flight_number . ' (' . $flight->route->departure_airport . ' → ' . $flight->route->arrival_airport . ')';
                    }
                }
                return implode(', ', array_slice($flights, 0, 3)) . (count($flights) > 3 ? '...' : '');
            },
        ],
        [
            'label' => 'Багаж',
            'value' => function($model) {
                $hasBaggage = false;
                foreach ($model->ticket as $ticket) {
                    if ($ticket->has_baggage) {
                        $hasBaggage = true;
                        break;
                    }
                }
                return $hasBaggage ? 'Да' : 'Нет';
            },
        ],
    ];
}

// Колонки для вкладки "Места на рейсах"
if ($activeTab === 'seats') {
    $columns = [
        [
            'attribute' => 'flight.flight_number',
            'label' => 'Номер рейса',
        ],
        [
            'attribute' => 'seat_number',
            'label' => 'Номер места',
        ],
        [
            'attribute' => 'ticket_price',
            'format' => 'decimal',
            'label' => 'Цена билета',
        ],
        [
            'attribute' => 'status',
            'value' => function($model) {
                $statuses = [
                    'booked' => 'Забронирован',
                    'confirmed' => 'Подтвержден',
                    'cancelled' => 'Отменен',
                    'refunded' => 'Возвращен',
                ];
                return $statuses[$model->status] ?? $model->status;
            },
            'label' => 'Статус',
        ],
        [
            'attribute' => 'flight.route.departure_airport',
            'label' => 'Вылет',
        ],
        [
            'attribute' => 'flight.route.arrival_airport',
            'label' => 'Прилет',
        ],
        [
            'attribute' => 'flight.departure_time',
            'format' => 'datetime',
            'label' => 'Время вылета',
        ],
        [
            'label' => 'Пассажир',
            'value' => function($model) {
                return $model->passenger ? $model->passenger->first_name . ' ' . $model->passenger->last_name : 'Не указан';
            },
        ],
        [
            'attribute' => 'has_baggage',
            'value' => function($model) {
                return $model->has_baggage ? 'Да' : 'Нет';
            },
            'label' => 'Багаж',
        ],
    ];
}

// Колонки для вкладки "Сданные билеты"
if ($activeTab === 'refunds') {
    $columns = [
        [
            'attribute' => 'refund_date',
            'format' => 'datetime',
            'label' => 'Дата возврата',
        ],
        [
            'attribute' => 'refund_amount',
            'format' => 'decimal',
            'label' => 'Сумма возврата',
        ],
        [
            'label' => 'Пассажир',
            'value' => function($model) {
                return $model->ticket->passenger->first_name . ' ' . $model->ticket->passenger->last_name;
            },
        ],
        [
            'attribute' => 'ticket.passenger.gender',
            'value' => function($model) {
                return $model->ticket->passenger->gender == 'M' ? 'Мужской' : 'Женский';
            },
            'label' => 'Пол',
        ],
        [
            'label' => 'Возраст',
            'value' => function($model) {
                $birthDate = new \DateTime($model->ticket->passenger->birth_date);
                $today = new \DateTime();
                return $today->diff($birthDate)->y . ' лет';
            },
        ],
        [
            'attribute' => 'ticket.flight.flight_number',
            'label' => 'Номер рейса',
        ],
        [
            'attribute' => 'ticket.seat_number',
            'label' => 'Место',
        ],
        [
            'attribute' => 'ticket.ticket_price',
            'format' => 'decimal',
            'label' => 'Цена билета',
        ],
        [
            'attribute' => 'ticket.flight.route.departure_airport',
            'label' => 'Вылет',
        ],
        [
            'attribute' => 'ticket.flight.route.arrival_airport',
            'label' => 'Прилет',
        ],
        [
            'attribute' => 'ticket.flight.departure_time',
            'format' => 'datetime',
            'label' => 'Время вылета',
        ],
        [
            'label' => 'Обработал',
            'value' => function($model) {
                return $model->employee ? 
                    $model->employee->first_name . ' ' . $model->employee->last_name : 
                    'Не указан';
            },
        ],
    ];
}

?>
<div class="passenger-ticket-search-results">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <strong>Найдено записей:</strong> <?= $dataProvider->getTotalCount() ?>
        <?= Html::a('Новый поиск', ['search', 'tab' => $activeTab], ['class' => 'btn btn-default btn-xs']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
    ]); ?>
</div>