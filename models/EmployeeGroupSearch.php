<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class EmployeeGroupSearch extends Model
{
    // Общие поля для всех запросов
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

    public $position_type;
    public $team_id;
    public $medical_result;
    public $medical_year;

    public function rules()
    {
        return [
            // Общие правила
            [['department_id', 'min_experience', 'max_experience', 'min_age', 'max_age', 'children_count', 'team_id'], 'integer'],
            [['min_salary', 'max_salary'], 'number', 'min' => 0],
            [['gender'], 'in', 'range' => ['M', 'F']],
            [['has_children'], 'boolean'],
            [['position_type'], 'in', 'range' => ['all', 'managers', 'regular']],
            [['medical_result'], 'in', 'range' => ['passed', 'failed', 'all']],
            [['medical_year'], 'integer', 'min' => 2000, 'max' => 2030],
            
            // Валидация диапазонов
            ['max_experience', 'compare', 'compareAttribute' => 'min_experience', 'operator' => '>='],
            ['max_age', 'compare', 'compareAttribute' => 'min_age', 'operator' => '>='],
            ['max_salary', 'compare', 'compareAttribute' => 'min_salary', 'operator' => '>='],
        ];
    }

    public function attributeLabels()
    {
        return [
            // Общие labels
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
            'team_id' => 'Бригада',
            'medical_result' => 'Результат медосмотра',
            'medical_year' => 'Год медосмотра',
        ];
    }

    public function getDepartmentsList()
    {
        return ArrayHelper::map(Departments::find()->all(), 'department_id', 'department_name');
    }

    public function getTeamsList()
    {
        return ArrayHelper::map(Teams::find()->all(), 'team_id', 'team_name');
    }

    public function getGenderList()
    {
        return ['M' => 'Мужской', 'F' => 'Женский'];
    }

    public function getPositionTypeList()
    {
        return [
            'all' => 'Все сотрудники',
            'managers' => 'Только начальники отделов',
            'regular' => 'Обычные сотрудники',
        ];
    }

    public function getMedicalResultList()
    {
        return [
            'all' => 'Любой результат',
            'passed' => 'Прошел медосмотр',
            'failed' => 'Не прошел медосмотр',
        ];
    }

    public function getHasChildrenList()
    {
        return ['1' => 'Есть дети', '0' => 'Нет детей'];
    }

    
    //Поиск для всех сотрудников (запрос 1)

    public function searchAllEmployees()
    {
        $query = Employees::find()->with('position.department');

        // Фильтр по типу сотрудника (начальники/обычные)
        if ($this->position_type === 'managers') {
            $query->joinWith('departmentManager');
            $query->andWhere(['IS NOT', 'department_managers.employee_id', null]);
        } elseif ($this->position_type === 'regular') {
            $query->joinWith('departmentManager');
            $query->andWhere(['IS', 'department_managers.employee_id', null]);
        }

        // общие фильтры
        $this->applyCommonFilters($query);

        return $this->createDataProvider($query);
    }


    //Поиск работников в бригадах (запрос 2)
    public function searchTeamEmployees()
    {
        $query = Employees::find()
            ->joinWith(['teamMember.team.department'])
            ->groupBy('employees.employee_id');

        // Фильтр по бригаде
        if (!empty($this->team_id)) {
            $query->andWhere(['team_members.team_id' => $this->team_id]);
        }

        // общие фильтры
        $this->applyCommonFilters($query);

        return $this->createDataProvider($query);
    }


    //Поиск пилотов с медосмотрами (запрос 3)
    public function searchPilotsMedical()
    {
        $query = Employees::find()
            ->joinWith(['medicalExamination', 'position'])
            ->andWhere(['positions.position_id' => [1, 2]]) // только пилоты
            ->groupBy('employees.employee_id');

        // Фильтр по результату медосмотра
        if ($this->medical_result === 'passed') {
            $query->andWhere(['medical_examinations.result' => 'passed']);
        } elseif ($this->medical_result === 'failed') {
            $query->andWhere(['medical_examinations.result' => 'failed']);
        }

        // Фильтр по году медосмотра
        if (!empty($this->medical_year)) {
            $query->andWhere(['YEAR(medical_examinations.examination_date)' => $this->medical_year]);
        }

        // общие фильтры
        $this->applyCommonFilters($query);

        return $this->createDataProvider($query);
    }


    //Общие фильтры для всех запросов
    private function applyCommonFilters($query)
    {
        // Фильтр по отделу
        if (!empty($this->department_id)) {
            $query->joinWith('position');
            $query->andWhere(['positions.department_id' => $this->department_id]);
        }

        // Фильтр по стажу
        if (!empty($this->min_experience)) {
            $minDate = (new \DateTime())->modify("-{$this->min_experience} years")->format('Y-m-d');
            $query->andWhere(['<=', 'employees.hire_date', $minDate]);
        }
        if (!empty($this->max_experience)) {
            $maxDate = (new \DateTime())->modify("-{$this->max_experience} years")->format('Y-m-d');
            $query->andWhere(['>=', 'employees.hire_date', $maxDate]);
        }

        // Фильтр по полу
        if (!empty($this->gender)) {
            $query->andWhere(['employees.gender' => $this->gender]);
        }

        // Фильтр по возрасту
        if (!empty($this->min_age)) {
            $maxBirthDate = (new \DateTime())->modify("-{$this->min_age} years")->format('Y-m-d');
            $query->andWhere(['<=', 'employees.birth_date', $maxBirthDate]);
        }
        if (!empty($this->max_age)) {
            $minBirthDate = (new \DateTime())->modify("-{$this->max_age} years")->format('Y-m-d');
            $query->andWhere(['>=', 'employees.birth_date', $minBirthDate]);
        }

        // Фильтр по наличию детей
        if ($this->has_children !== '') {
            if ($this->has_children) {
                $query->andWhere(['>', 'employees.children_count', 0]);
            } else {
                $query->andWhere(['employees.children_count' => 0]);
            }
        }

        // Фильтр по количеству детей
        if (!empty($this->children_count)) {
            $query->andWhere(['employees.children_count' => $this->children_count]);
        }

        // Фильтр по зарплате
        if (!empty($this->min_salary)) {
            $query->andWhere(['>=', 'employees.salary', $this->min_salary]);
        }
        if (!empty($this->max_salary)) {
            $query->andWhere(['<=', 'employees.salary', $this->max_salary]);
        }
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
    }
}