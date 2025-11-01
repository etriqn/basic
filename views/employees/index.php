<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Сотрудники';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="employees-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'employee_id',
                'label' => 'ID',
            ],
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
                'label' => 'Дата рождения',
            ],
            [
                'attribute' => 'gender',
                'label' => 'Пол',
            ],
            [
                'attribute' => 'hire_date',
                'label' => 'Дата приёма',
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
            [
                'attribute' => 'salary',
                'label' => 'Зарплата',
            ],
            [
                'attribute' => 'children_count',
                'label' => 'Количество детей',
            ],
            [
                'attribute' => 'phone_number',
                'label' => 'Телефон',
            ],
            'email',
        ],
    ]); ?>
</div>