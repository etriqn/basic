<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class EmployeeSearch extends Model
{
    public $department_id;
    public $min_experience;
    public $max_experience;
    public $gender;
    public $min_age;
    public $max_age;
    public $has_children;
    public $children_count;
    public $min_salary;
    public $max_salary;
    public $position_type; // все, начальники, обычные

    public function rules()
    {
        return [
            [['department_id', 'min_experience', 'max_experience', 'min_age', 'max_age', 'children_count'], 'integer'],
            [['min_salary', 'max_salary'], 'number', 'min' => 0],
            [['gender'], 'in', 'range' => ['M', 'F']],
            [['has_children'], 'boolean'],
            [['position_type'], 'in', 'range' => ['all', 'managers', 'regular']],
            
            // Валидация диапазонов
            ['max_experience', 'compare', 'compareAttribute' => 'min_experience', 'operator' => '>=', 
             'message' => 'Максимальный стаж должен быть больше или равен минимальному'],
            ['max_age', 'compare', 'compareAttribute' => 'min_age', 'operator' => '>=', 
             'message' => 'Максимальный возраст должен быть больше или равен минимальному'],
            ['max_salary', 'compare', 'compareAttribute' => 'min_salary', 'operator' => '>=', 
             'message' => 'Максимальная зарплата должна быть больше или равна минимальной'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'department_id' => 'Отдел',
            'min_experience' => 'Минимальный стаж (лет)',
            'max_experience' => 'Максимальный стаж (лет)',
            'gender' => 'Пол',
            'min_age' => 'Минимальный возраст',
            'max_age' => 'Максимальный возраст',
            'has_children' => 'Наличие детей',
            'children_count' => 'Количество детей',
            'min_salary' => 'Минимальная зарплата',
            'max_salary' => 'Максимальная зарплата',
            'position_type' => 'Тип сотрудника',
        ];
    }

    public function getDepartmentsList()
    {
        return ArrayHelper::map(Departments::find()->all(), 'department_id', 'department_name');
    }

    public function getGenderList()
    {
        return [
            'M' => 'Мужской',
            'F' => 'Женский',
        ];
    }

    public function getPositionTypeList()
    {
        return [
            'all' => 'Все сотрудники',
            'managers' => 'Только начальники отделов',
            'regular' => 'Обычные сотрудники',
        ];
    }

    public function getHasChildrenList()
    {
        return [
            '1' => 'Есть дети',
            '0' => 'Нет детей',
        ];
    }

    
    public function search($params = [])
    {
        $query = Employees::find()->with('position.department');

        if (!empty($params)) {
            $this->load($params);
        }

        if ($this->validate()) {
            // Фильтр по типу сотрудника (все, начальники, обычные)
            if ($this->position_type === 'managers') {
                $query->joinWith('departmentManager');
                $query->andWhere(['IS NOT', 'department_managers.employee_id', null]);
            } elseif ($this->position_type === 'regular') {
                $query->joinWith('departmentManager');
                $query->andWhere(['IS', 'department_managers.employee_id', null]);
            }

            // Фильтр по отделу
            if (!empty($this->department_id)) {
                $query->joinWith('position');
                $query->andWhere(['positions.department_id' => $this->department_id]);
            }

            // Фильтр по стажу (опыту работы)
            if (!empty($this->min_experience)) {
                $minDate = (new \DateTime())->modify("-{$this->min_experience} years")->format('Y-m-d');
                $query->andWhere(['<=', 'hire_date', $minDate]);
            }
            if (!empty($this->max_experience)) {
                $maxDate = (new \DateTime())->modify("-{$this->max_experience} years")->format('Y-m-d');
                $query->andWhere(['>=', 'hire_date', $maxDate]);
            }

            // Фильтр по полу
            if (!empty($this->gender)) {
                $query->andWhere(['gender' => $this->gender]);
            }

            // Фильтр по возрасту
            if (!empty($this->min_age)) {
                $maxBirthDate = (new \DateTime())->modify("-{$this->min_age} years")->format('Y-m-d');
                $query->andWhere(['<=', 'birth_date', $maxBirthDate]);
            }
            if (!empty($this->max_age)) {
                $minBirthDate = (new \DateTime())->modify("-{$this->max_age} years")->format('Y-m-d');
                $query->andWhere(['>=', 'birth_date', $minBirthDate]);
            }

            // Фильтр по наличию детей
            if ($this->has_children !== '') {
                if ($this->has_children) {
                    $query->andWhere(['>', 'children_count', 0]);
                } else {
                    $query->andWhere(['children_count' => 0]);
                }
            }

            // Фильтр по количеству детей
            if (!empty($this->children_count)) {
                $query->andWhere(['children_count' => $this->children_count]);
            }

            // Фильтр по зарплате
            if (!empty($this->min_salary)) {
                $query->andWhere(['>=', 'salary', $this->min_salary]);
            }
            if (!empty($this->max_salary)) {
                $query->andWhere(['<=', 'salary', $this->max_salary]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['last_name' => SORT_ASC],
                'attributes' => [
                    'employee_id',
                    'first_name',
                    'last_name',
                    'birth_date',
                    'hire_date',
                    'gender',
                    'salary',
                    'children_count',
                    'position.position_name' => [
                        'asc' => ['positions.position_name' => SORT_ASC],
                        'desc' => ['positions.position_name' => SORT_DESC],
                    ],
                    'position.department.department_name' => [
                        'asc' => ['departments.department_name' => SORT_ASC],
                        'desc' => ['departments.department_name' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        return $dataProvider;
    }
}