<?php

use yii\helpers\Html;
use yii\grid\GridView;

$titles = [
    'all' => 'Результаты поиска всех сотрудников',
    'teams' => 'Результаты поиска сотрудников в бригадах', 
    'medical' => 'Результаты поиска пилотов с медосмотрами',
];

$this->title = $titles[$activeTab] ?? 'Результаты поиска';
$this->params['breadcrumbs'][] = ['label' => 'Поиск сотрудников (группа)', 'url' => ['search', 'tab' => $activeTab]];
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    'employee_id',
    'first_name',
    'last_name',
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
        'attribute' => 'hire_date',
        'value' => function($model) {
            $hireDate = new \DateTime($model->hire_date);
            $today = new \DateTime();
            $experience = $today->diff($hireDate)->y;
            return $model->hire_date . ' (' . $experience . ' лет)';
        },
        'label' => 'Дата приема (Стаж)',
    ],
    [
        'attribute' => 'gender',
        'value' => function($model) {
            return $model->gender == 'M' ? 'Мужской' : 'Женский';
        }
    ],
    [
        'attribute' => 'position_id',
        'value' => 'position.position_name',
        'label' => 'Должность',
    ],
    [
        'attribute' => 'department',
        'value' => 'position.department.department_name',
        'label' => 'Отдел',
    ],
    'salary:decimal',
    'children_count',
];

// Колонки для вкладки "Сотрудники в бригадах"
if ($activeTab === 'teams') {
    array_splice($columns, 6, 0, [
        [
            'attribute' => 'team_name',
            'value' => function($model) {
                return $model->teamMember[0]->team->team_name ?? 'Не в бригаде';
            },
            'label' => 'Бригада',
        ]
    ]);
}

// Колонки для вкладки "Пилоты с медосмотрами"
if ($activeTab === 'medical') {
    array_splice($columns, 6, 0, [
        [
            'attribute' => 'medical_result',
            'value' => function($model) {
                return $model->medicalExamination[0]->result ?? 'Нет данных';
            },
            'label' => 'Результат медосмотра',
        ],
        [
            'attribute' => 'medical_date',
            'value' => function($model) {
                return $model->medicalExamination[0]->examination_date ?? 'Нет данных';
            },
            'label' => 'Дата медосмотра',
        ]
    ]);
}

?>
<div class="employee-group-search-results">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <strong>Найдено сотрудников:</strong> <?= $dataProvider->getTotalCount() ?>
        <?= Html::a('Новый поиск', ['search', 'tab' => $activeTab], ['class' => 'btn btn-default btn-xs']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
    ]); ?>
</div>