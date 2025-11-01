<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\PassengerTicketSearch;
use Yii;

class PassengerTicketController extends Controller
{
    public function actionSearch($tab = 'passengers')
    {
        $searchModel = new PassengerTicketSearch();
        $searchModel->activeTab = $tab;
        
        if ($searchModel->load(Yii::$app->request->post()) && $searchModel->validate()) {
            
            $dataProvider = match($tab) {
                'passengers' => $searchModel->searchPassengers(),
                'seats' => $searchModel->searchSeats(),
                'refunds' => $searchModel->searchRefundedTickets(),
                default => $searchModel->searchPassengers(),
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