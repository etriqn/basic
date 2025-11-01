<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\Employees;
use app\models\EmployeeSearch;
use Yii;

class EmployeesController extends Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Employees::find()->with('position.department'),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSearch()
    {
        $searchModel = new EmployeeSearch();
        
        if ($searchModel->load(Yii::$app->request->post()) && $searchModel->validate()) {
            $dataProvider = $searchModel->search();
            
            return $this->render('search-results', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('search', [
            'model' => $searchModel,
        ]);
    }
}