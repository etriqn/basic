<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\EmployeeGroupSearch;
use Yii;

class EmployeeGroupController extends Controller
{
    public function actionSearch($tab = 'all')
    {
        $searchModel = new EmployeeGroupSearch();
        
        if ($searchModel->load(Yii::$app->request->post()) && $searchModel->validate()) {
            // Выбираем метод поиска в зависимости от вкладки
            $dataProvider = match($tab) {
                'all' => $searchModel->searchAllEmployees(),
                'teams' => $searchModel->searchTeamEmployees(),
                'medical' => $searchModel->searchPilotsMedical(),
                default => $searchModel->searchAllEmployees(),
            };
            
            return $this->render('search-results', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'activeTab' => $tab,
            ]);
        }

        return $this->render('search', [
            'model' => $searchModel,
            'activeTab' => $tab,
        ]);
    }
}