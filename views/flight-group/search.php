<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Поиск рейсов (группа запросов)';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="flight-group-search">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Навигация вкладок -->
    <ul class="nav nav-tabs">
        <li class="<?= $activeTab === 'flights' ? 'active' : '' ?>">
            <?= Html::a('Рейсы по параметрам', ['search', 'tab' => 'flights']) ?>
        </li>
        <li class="<?= $activeTab === 'cancelled' ? 'active' : '' ?>">
            <?= Html::a('Отмененные рейсы', ['search', 'tab' => 'cancelled']) ?>
        </li>
        <li class="<?= $activeTab === 'delayed' ? 'active' : '' ?>">
            <?= Html::a('Задержанные рейсы', ['search', 'tab' => 'delayed']) ?>
        </li>
        <li class="<?= $activeTab === 'aircraft' ? 'active' : '' ?>">
            <?= Html::a('Рейсы по самолетам', ['search', 'tab' => 'aircraft']) ?>
        </li>
        <li class="<?= $activeTab === 'category' ? 'active' : '' ?>">
            <?= Html::a('Рейсы по категориям', ['search', 'tab' => 'category']) ?>
        </li>
    </ul>

    <div class="flight-group-form">
        <?php $form = ActiveForm::begin(); ?>

        <!-- Скрытое поле для активной вкладки -->
        <?= Html::activeHiddenInput($model, 'activeTab') ?>

        <!-- Контент активной вкладки -->
        <div class="tab-content" style="padding: 20px 0;">
            <?php if ($activeTab === 'flights'): ?>
                <!-- Вкладка: Рейсы по параметрам -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Маршрут</h4>
                        <?= $form->field($model, 'route_id')->dropDownList(
                            $model->getRoutesList(), 
                            ['prompt' => 'Все маршруты']
                        ) ?>
                        <?= $form->field($model, 'departure_airport')->textInput([
                            'placeholder' => 'Например: Москва'
                        ]) ?>
                        <?= $form->field($model, 'arrival_airport')->textInput([
                            'placeholder' => 'Например: Лондон'
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <h4>Параметры рейса</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'min_duration')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'placeholder' => 'Мин. длительность'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'max_duration')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'placeholder' => 'Макс. длительность'
                                ]) ?>
                            </div>
                        </div>
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
                        <?= $form->field($model, 'flight_status')->dropDownList(
                            $model->getFlightStatusList()
                        ) ?>
                    </div>
                </div>

            <?php elseif ($activeTab === 'cancelled'): ?>
                <!-- Вкладка: Отмененные рейсы -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Маршрут</h4>
                        <?= $form->field($model, 'route_id')->dropDownList(
                            $model->getRoutesList(), 
                            ['prompt' => 'Все маршруты']
                        ) ?>
                        <?= $form->field($model, 'departure_airport')->textInput([
                            'placeholder' => 'Например: Москва'
                        ]) ?>
                        <?= $form->field($model, 'arrival_airport')->textInput([
                            'placeholder' => 'Например: Лондон'
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <h4>Период</h4>
                        <?= $form->field($model, 'date_from')->input('date') ?>
                        <?= $form->field($model, 'date_to')->input('date') ?>
                    </div>
                </div>

            <?php elseif ($activeTab === 'delayed'): ?>
                <!-- Вкладка: Задержанные рейсы -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Параметры задержки</h4>
                        <?= $form->field($model, 'delay_reason')->dropDownList(
                            $model->getDelayReasonList()
                        ) ?>
                        <?= $form->field($model, 'route_id')->dropDownList(
                            $model->getRoutesList(), 
                            ['prompt' => 'Все маршруты']
                        ) ?>
                    </div>
                    <div class="col-md-6">
                        <h4>Период</h4>
                        <?= $form->field($model, 'date_from')->input('date') ?>
                        <?= $form->field($model, 'date_to')->input('date') ?>
                    </div>
                </div>

            <?php elseif ($activeTab === 'aircraft'): ?>
                <!-- Вкладка: Рейсы по самолетам -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Самолет</h4>
                        <?= $form->field($model, 'aircraft_type_id')->dropDownList(
                            $model->getAircraftTypesList(), 
                            ['prompt' => 'Все типы самолетов']
                        ) ?>
                    </div>
                    <div class="col-md-6">
                        <h4>Параметры рейса</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'min_duration')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'placeholder' => 'Мин. длительность'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'max_duration')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'placeholder' => 'Макс. длительность'
                                ]) ?>
                            </div>
                        </div>
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
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h4>Период</h4>
                        <?= $form->field($model, 'date_from')->input('date') ?>
                        <?= $form->field($model, 'date_to')->input('date') ?>
                    </div>
                </div>

            <?php elseif ($activeTab === 'category'): ?>
                <!-- Вкладка: Рейсы по категориям -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Категория и самолет</h4>
                        <?= $form->field($model, 'category_id')->dropDownList(
                            $model->getFlightCategoriesList(), 
                            ['prompt' => 'Все категории']
                        ) ?>
                        <?= $form->field($model, 'aircraft_type_id')->dropDownList(
                            $model->getAircraftTypesList(), 
                            ['prompt' => 'Все типы самолетов']
                        ) ?>
                    </div>
                    <div class="col-md-6">
                        <h4>Направление</h4>
                        <?= $form->field($model, 'departure_airport')->textInput([
                            'placeholder' => 'Аэропорт вылета'
                        ]) ?>
                        <?= $form->field($model, 'arrival_airport')->textInput([
                            'placeholder' => 'Аэропорт прилета'
                        ]) ?>
                    </div>
                </div>

            <?php endif; ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Найти рейсы', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Сбросить', ['search', 'tab' => $activeTab], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>