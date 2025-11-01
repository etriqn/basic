<?php

use yii\helpers\Html;
use yii\grid\GridView;

$titles = [
    'list' => 'Результаты поиска самолетов',
    'inspections' => 'Результаты поиска по техосмотрам', 
    'repairs' => 'Результаты поиска по ремонтам',
];

$this->title = $titles[$activeTab] ?? 'Результаты поиска';
$this->params['breadcrumbs'][] = ['label' => 'Поиск самолетов (группа)', 'url' => ['search', 'tab' => $activeTab]];
$this->params['breadcrumbs'][] = $this->title;

// Колонки для всех вкладок
$columns = [
    [
        'attribute' => 'registration_number',
        'label' => 'Регистрационный номер',
    ],
    [
        'attribute' => 'aircraftType.type_name',
        'label' => 'Тип самолета',
    ],
    [
        'attribute' => 'aircraftType.manufacturer',
        'label' => 'Производитель',
    ],
    [
        'attribute' => 'manufacture_date',
        'format' => 'date',
        'label' => 'Дата производства',
    ],
    [
        'attribute' => 'status',
        'value' => function($model) {
            $statuses = [
                'active' => 'Активный',
                'maintenance' => 'На обслуживании',
                'retired' => 'Списанный'
            ];
            return $statuses[$model->status] ?? $model->status;
        },
        'label' => 'Статус',
    ],
];

// Колонки для вкладки "Список самолетов"
if ($activeTab === 'list') {
    $columns = array_merge($columns, [
        /*[
            'attribute' => 'flight_count',
            'label' => 'Количество рейсов',
        ],*/ //v1

        /*[
            'label' => 'Количество рейсов',
            'value' => function($model) {
                // через вычисленное поле
                return $model->flight_count ?? 0;
            },
        ],*/ //v2
        [
            'attribute' => 'aircraft_age',
            'value' => function($model) {
                $manufactureYear = date('Y', strtotime($model->manufacture_date));
                $currentYear = date('Y');
                return $currentYear - $manufactureYear . ' лет';
            },
            'label' => 'Возраст',
        ]
    ]);
}

// Кколонки для вкладки "Техосмотры"
if ($activeTab === 'inspections') {
    $columns = array_merge($columns, [
        [
            'label' => 'Дата последнего осмотра',
            'value' => function($model) {
                $lastInspection = $model->technicalInspection[0] ?? null;
                return $lastInspection ? Yii::$app->formatter->asDatetime($lastInspection->inspection_date) : '';
            },
        ],
        [
            'label' => 'Тип осмотра',
            'value' => function($model) {
                $lastInspection = $model->technicalInspection[0] ?? null;
                if (!$lastInspection) return '';
                
                $types = [
                    'routine' => 'Плановый',
                    'preflight' => 'Предполетный', 
                    'special' => 'Специальный'
                ];
                return $types[$lastInspection->inspection_type] ?? $lastInspection->inspection_type;
            },
        ],
        [
            'label' => 'Результат',
            'value' => function($model) {
                $lastInspection = $model->technicalInspection[0] ?? null;
                if (!$lastInspection) return '';
                
                $results = [
                    'passed' => 'Пройден',
                    'failed' => 'Не пройден',
                    'requires_repair' => 'Требует ремонта'
                ];
                return $results[$lastInspection->result] ?? $lastInspection->result;
            },
        ]
    ]);
}

// Колонки для вкладки "Ремонты"
if ($activeTab === 'repairs') {
    $columns = array_merge($columns, [
        [
            'label' => 'Дата последнего ремонта',
            'value' => function($model) {
                $lastRepair = $model->repair[0] ?? null;
                return $lastRepair ? Yii::$app->formatter->asDate($lastRepair->start_date) : '';
            },
        ],
        [
            'label' => 'Тип ремонта',
            'value' => function($model) {
                $lastRepair = $model->repair[0] ?? null;
                return $lastRepair ? $lastRepair->repair_type : '';
            },
        ],
        [
            'label' => 'Всего ремонтов',
            'value' => function($model) {
                return count($model->repair);
            },
        ]
    ]);
}

?>
<div class="aircraft-group-search-results">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <strong>Найдено самолетов:</strong> <?= $dataProvider->getTotalCount() ?>
        <?= Html::a('Новый поиск', ['search', 'tab' => $activeTab], ['class' => 'btn btn-default btn-xs']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
    ]); ?>
</div>