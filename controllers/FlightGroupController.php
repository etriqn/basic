<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\FlightGroupSearch;
use Yii;

class FlightGroupController extends Controller
{
    public function actionSearch($tab = 'flights')
    {
        $searchModel = new FlightGroupSearch();
        $searchModel->activeTab = $tab;
        
        if ($searchModel->load(Yii::$app->request->post()) && $searchModel->validate()) {
            // Выбираем метод поиска в зависимости от вкладки
            $dataProvider = match($tab) {
                'flights' => $searchModel->searchFlights(),
                'cancelled' => $searchModel->searchCancelledFlights(),
                'delayed' => $searchModel->searchDelayedFlights(),
                'aircraft' => $searchModel->searchFlightsByAircraft(),
                'category' => $searchModel->searchFlightsByCategory(),
                default => $searchModel->searchFlights(),
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