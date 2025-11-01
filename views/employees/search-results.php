<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Результаты поиска сотрудников';
$this->params['breadcrumbs'][] = ['label' => 'Поиск сотрудников', 'url' => ['search']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="employees-search-results">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <strong>Найдено сотрудников:</strong> <?= $dataProvider->getTotalCount() ?>
        <?= Html::a('Новый поиск', ['search'], ['class' => 'btn btn-default btn-xs']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
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
        ],
    ]); ?>
</div>