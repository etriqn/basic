<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Поиск пассажиров и билетов (группа запросов)';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="passenger-ticket-search">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Навигация вкладок -->
    <ul class="nav nav-tabs">
        <li class="<?= $activeTab === 'passengers' ? 'active' : '' ?>">
            <?= Html::a('Пассажиры на рейсах', ['search', 'tab' => 'passengers']) ?>
        </li>
        <li class="<?= $activeTab === 'seats' ? 'active' : '' ?>">
            <?= Html::a('Места на рейсах', ['search', 'tab' => 'seats']) ?>
        </li>
        <li class="<?= $activeTab === 'refunds' ? 'active' : '' ?>">
            <?= Html::a('Сданные билеты', ['search', 'tab' => 'refunds']) ?>
        </li>
    </ul>

    <div class="passenger-ticket-form">
        <?php $form = ActiveForm::begin(); ?>

        <!-- Скрытое поле для активной вкладки -->
        <?= Html::activeHiddenInput($model, 'activeTab') ?>

        <!-- Контент активной вкладки -->
        <div class="tab-content" style="padding: 20px 0;">
            <?php if ($activeTab === 'passengers'): ?>
                <!-- Вкладка: Пассажиры на рейсах -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Параметры рейса</h4>
                        <?= $form->field($model, 'flight_id')->dropDownList(
                            $model->getFlightsList(), 
                            ['prompt' => 'Все рейсы']
                        ) ?>
                        <?= $form->field($model, 'departure_date')->input('date') ?>
                        <?= $form->field($model, 'route_id')->dropDownList(
                            $model->getRoutesList(), 
                            ['prompt' => 'Все маршруты']
                        ) ?>
                        <?= $form->field($model, 'has_baggage')->dropDownList(
                            $model->getHasBaggageList()
                        ) ?>
                    </div>
                    <div class="col-md-6">
                        <h4>Параметры пассажиров</h4>
                        <?= $form->field($model, 'gender')->dropDownList(
                            $model->getGenderList()
                        ) ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'min_age')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'max' => 120,
                                    'placeholder' => 'Мин. возраст'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'max_age')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'max' => 120,
                                    'placeholder' => 'Макс. возраст'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php elseif ($activeTab === 'seats'): ?>
                <!-- Вкладка: Места на рейсах -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Параметры рейса</h4>
                        <?= $form->field($model, 'flight_id')->dropDownList(
                            $model->getFlightsList(), 
                            ['prompt' => 'Все рейсы']
                        ) ?>
                        <?= $form->field($model, 'departure_date')->input('date') ?>
                        <?= $form->field($model, 'route_id')->dropDownList(
                            $model->getRoutesList(), 
                            ['prompt' => 'Все маршруты']
                        ) ?>
                    </div>
                    <div class="col-md-6">
                        <h4>Параметры билетов</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'min_price')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'step' => 0.01,
                                    'placeholder' => 'Мин. цена'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'max_price')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'step' => 0.01,
                                    'placeholder' => 'Макс. цена'
                                ]) ?>
                            </div>
                        </div>
                        <?= $form->field($model, 'ticket_status')->dropDownList(
                            $model->getTicketStatusList()
                        ) ?>
                    </div>
                </div>

            <?php elseif ($activeTab === 'refunds'): ?>
                <!-- Вкладка: Сданные билеты -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Параметры рейса</h4>
                        <?= $form->field($model, 'flight_id')->dropDownList(
                            $model->getFlightsList(), 
                            ['prompt' => 'Все рейсы']
                        ) ?>
                        <?= $form->field($model, 'departure_date')->input('date') ?>
                        <?= $form->field($model, 'route_id')->dropDownList(
                            $model->getRoutesList(), 
                            ['prompt' => 'Все маршруты']
                        ) ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'min_price')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'step' => 0.01,
                                    'placeholder' => 'Мин. цена'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'max_price')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'step' => 0.01,
                                    'placeholder' => 'Макс. цена'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4>Параметры пассажиров</h4>
                        <?= $form->field($model, 'gender')->dropDownList(
                            $model->getGenderList()
                        ) ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'min_age')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'max' => 120,
                                    'placeholder' => 'Мин. возраст'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'max_age')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'max' => 120,
                                    'placeholder' => 'Макс. возраст'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Сбросить', ['search', 'tab' => $activeTab], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>