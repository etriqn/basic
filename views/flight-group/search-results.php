<?php

use yii\helpers\Html;
use yii\grid\GridView;

$titles = [
    'flights' => 'Результаты поиска рейсов',
    'cancelled' => 'Результаты поиска отмененных рейсов', 
    'delayed' => 'Результаты поиска задержанных рейсов',
    'aircraft' => 'Результаты поиска рейсов по самолетам',
    'category' => 'Результаты поиска рейсов по категориям',
];

$this->title = $titles[$activeTab] ?? 'Результаты поиска';
$this->params['breadcrumbs'][] = ['label' => 'Поиск рейсов (группа)', 'url' => ['search', 'tab' => $activeTab]];
$this->params['breadcrumbs'][] = $this->title;

// колонки для всех вкладок
$columns = [
    [
        'attribute' => 'flight_number',
        'label' => 'Номер рейса',
    ],
    [
        'attribute' => 'route.departure_airport',
        'label' => 'Вылет',
    ],
    [
        'attribute' => 'route.arrival_airport',
        'label' => 'Прилет',
    ],
    [
        'attribute' => 'departure_time',
        'format' => 'datetime',
        'label' => 'Время вылета',
    ],
    [
        'attribute' => 'arrival_time',
        'format' => 'datetime',
        'label' => 'Время прилета',
    ],
    [
        'attribute' => 'status',
        'value' => function($model) {
            $statuses = [
                'scheduled' => 'Запланирован',
                'boarding' => 'Посадка',
                'departed' => 'Вылетел',
                'arrived' => 'Прибыл',
                'delayed' => 'Задержан',
                'cancelled' => 'Отменен',
            ];
            return $statuses[$model->status] ?? $model->status;
        },
        'label' => 'Статус',
    ],
];

// колонки для вкладки "Рейсы по параметрам"
if ($activeTab === 'flights') {
    $columns = array_merge($columns, [
        [
            'attribute' => 'route.estimated_duration_min',
            'label' => 'Длительность (мин)',
        ],
        [
            'attribute' => 'base_ticket_price',
            'format' => 'decimal',
            'label' => 'Цена билета',
        ],
        //[
        //    'attribute' => 'tickets_sold',
        //    'label' => 'Продано билетов',
        //],
    ]);
}

// колонки для вкладки "Отмененные рейсы"
if ($activeTab === 'cancelled') {
    $columns = array_merge($columns, [
        [
            'attribute' => 'aircraft.registration_number',
            'label' => 'Борт',
        ],
        [
            'attribute' => 'aircraft.aircraftType.type_name',
            'label' => 'Тип самолета',
        ],
        [
            'attribute' => 'tickets_sold',
            'label' => 'Продано билетов',
        ],
        [
            'attribute' => 'aircraft_capacity',
            'label' => 'Вместимость',
        ],
        [
            'attribute' => 'unused_seats',
            'label' => 'Невостребованные места',
        ],
        [
            'attribute' => 'unused_percentage',
            'label' => 'Процент невостребованных',
            'value' => function($model) {
                return $model->unused_percentage . '%';
            },
        ],
        //[
        //    'label' => 'Невостребовано мест',
        //    'value' => function($model) {
        //        return $model->aircraft_capacity - $model->tickets_sold;
        //    },
        //],
        //[
        //    'label' => 'Процент невостребованных',
        //    'value' => function($model) {
        //        if ($model->aircraft_capacity == 0) return '0%';
        //        $percentage = (($model->aircraft_capacity - $model->tickets_sold) / $model->aircraft_capacity) * 100;
        //        return round($percentage, 2) . '%';
        //    },
        //],
    ]);
}

// колонки для вкладки "Задержанные рейсы"
if ($activeTab === 'delayed') {
    $columns = array_merge($columns, [
        [
            'label' => 'Причина задержки',
            'value' => function($model) {
                $reasons = [
                    'weather' => 'Погода',
                    'technical' => 'Технические',
                    'crew' => 'Экипаж',
                    'air_traffic' => 'Воздушное движение',
                    'other' => 'Другие',
                ];
                // Используем связь flightDelay
                $delay = $model->flightDelay[0] ?? null;
                return $delay ? ($reasons[$delay->delay_reason] ?? $delay->delay_reason) : 'Не указана';
            },
        ],
        [
            'label' => 'Начало задержки',
            'value' => function($model) {
                $delay = $model->flightDelay[0] ?? null;
                return $delay ? Yii::$app->formatter->asDatetime($delay->delay_start) : '';
            },
        ],
        //[
        //    'attribute' => 'refunds_count',
        //    'label' => 'Сдано билетов',
        //],
        [
            'label' => 'Сдано билетов',
            'value' => function($model) {
                return $model->refunds_count ?? 0;
            },
        ],
    ]);
}

// колонки для вкладки "Рейсы по самолетам"
if ($activeTab === 'aircraft') {
    $columns = array_merge($columns, [
        [
            'attribute' => 'aircraft.aircraftType.type_name',
            'label' => 'Тип самолета',
        ],
        [
            'attribute' => 'route.estimated_duration_min',
            'label' => 'Длительность (мин)',
        ],
        [
            'attribute' => 'base_ticket_price',
            'format' => 'decimal',
            'label' => 'Цена билета',
        ],
        [
            'attribute' => 'tickets_sold',
            'label' => 'Продано билетов',
        ],
        //[
        //    'attribute' => 'avg_ticket_price',
        //    'format' => 'decimal',
        //    'label' => 'Средняя цена',
        //],
    ]);
}

// колонки для вкладки "Рейсы по категориям"
if ($activeTab === 'category') {
    $columns = array_merge($columns, [
        [
            'attribute' => 'flightCategory.category_name',
            'label' => 'Категория рейса',
        ],
        [
            'attribute' => 'aircraft.aircraftType.type_name',
            'label' => 'Тип самолета',
        ],
    ]);
}

?>
<div class="flight-group-search-results">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <strong>Найдено рейсов:</strong> <?= $dataProvider->getTotalCount() ?>
        <?= Html::a('Новый поиск', ['search', 'tab' => $activeTab], ['class' => 'btn btn-default btn-xs']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
    ]); ?>
</div>