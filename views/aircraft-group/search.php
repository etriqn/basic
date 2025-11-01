<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Поиск самолетов (группа запросов)';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="aircraft-group-search">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Навигация вкладок -->
    <ul class="nav nav-tabs">
        <li class="<?= $activeTab === 'list' ? 'active' : '' ?>">
            <?= Html::a('Список самолетов', ['search', 'tab' => 'list']) ?>
        </li>
        <li class="<?= $activeTab === 'inspections' ? 'active' : '' ?>">
            <?= Html::a('Техосмотры', ['search', 'tab' => 'inspections']) ?>
        </li>
        <li class="<?= $activeTab === 'repairs' ? 'active' : '' ?>">
            <?= Html::a('Ремонты', ['search', 'tab' => 'repairs']) ?>
        </li>
    </ul>

    <div class="aircraft-group-form">
        <?php $form = ActiveForm::begin(); ?>

        <!-- Скрытое поле для активной вкладки -->
        <?= Html::activeHiddenInput($model, 'activeTab') ?>

        <!-- Контент активной вкладки -->
        <div class="tab-content" style="padding: 20px 0;">
            <?php if ($activeTab === 'list'): ?>
                <!-- Вкладка: Список самолетов -->
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'aircraft_status')->dropDownList(
                            $model->getAircraftStatusList()
                        ) ?>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'min_flights')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'placeholder' => 'Мин. рейсов'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'max_flights')->textInput([
                                    'type' => 'number', 
                                    'min' => 0,
                                    'placeholder' => 'Макс. рейсов'
                                ]) ?>
                            </div>
                        </div>
                        <?= $form->field($model, 'aircraft_age')->textInput([
                            'type' => 'number', 
                            'min' => 0,
                            'placeholder' => 'Возраст в годах'
                        ]) ?>
                    </div>
                </div>

            <?php elseif ($activeTab === 'inspections'): ?>
                <!-- Вкладка: Техосмотры -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Период техосмотра</h4>
                        <?= $form->field($model, 'date_from')->input('date') ?>
                        <?= $form->field($model, 'date_to')->input('date') ?>
                    </div>
                    <div class="col-md-6">
                        <h4>Параметры техосмотра</h4>
                        <?= $form->field($model, 'inspection_type')->dropDownList(
                            $model->getInspectionTypeList()
                        ) ?>
                        <?= $form->field($model, 'inspection_result')->dropDownList(
                            $model->getInspectionResultList()
                        ) ?>
                        <?= $form->field($model, 'aircraft_status')->dropDownList(
                            $model->getAircraftStatusList()
                        ) ?>
                    </div>
                </div>

            <?php elseif ($activeTab === 'repairs'): ?>
                <!-- Вкладка: Ремонты -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Период ремонта</h4>
                        <?= $form->field($model, 'date_from')->input('date') ?>
                        <?= $form->field($model, 'date_to')->input('date') ?>
                    </div>
                    <div class="col-md-6">
                        <h4>Параметры ремонтов</h4>
                        <?= $form->field($model, 'repair_count')->textInput([
                            'type' => 'number', 
                            'min' => 0,
                            'placeholder' => 'Минимальное количество'
                        ]) ?>
                        <?= $form->field($model, 'aircraft_status')->dropDownList(
                            $model->getAircraftStatusList()
                        ) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Найти самолеты', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Сбросить', ['search', 'tab' => $activeTab], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>