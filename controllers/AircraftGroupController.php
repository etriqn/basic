<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\AircraftGroupSearch;
use Yii;

class AircraftGroupController extends Controller
{
    public function actionSearch($tab = 'list')
    {
        $searchModel = new AircraftGroupSearch();
        $searchModel->activeTab = $tab;
        
        if ($searchModel->load(Yii::$app->request->post()) && $searchModel->validate()) {
            // Выбираем метод поиска в зависимости от вкладки
            $dataProvider = match($tab) {
                'list' => $searchModel->searchAircraftList(),
                'inspections' => $searchModel->searchTechInspections(),
                'repairs' => $searchModel->searchRepairs(),
                default => $searchModel->searchAircraftList(),
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