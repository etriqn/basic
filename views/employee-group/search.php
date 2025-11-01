<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Поиск сотрудников (группа запросов)';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="employee-group-search">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Навигация вкладок -->
    <ul class="nav nav-tabs">
        <li class="<?= $activeTab === 'all' ? 'active' : '' ?>">
            <?= Html::a('Все сотрудники', ['search', 'tab' => 'all']) ?>
        </li>
        <li class="<?= $activeTab === 'teams' ? 'active' : '' ?>">
            <?= Html::a('Сотрудники в бригадах', ['search', 'tab' => 'teams']) ?>
        </li>
        <li class="<?= $activeTab === 'medical' ? 'active' : '' ?>">
            <?= Html::a('Пилоты с медосмотрами', ['search', 'tab' => 'medical']) ?>
        </li>
    </ul>

    <div class="employee-group-form">
        <?php $form = ActiveForm::begin(); ?>

        <!-- Контент активной вкладки -->
        <div class="tab-content" style="padding: 20px 0;">
            <?php if ($activeTab === 'all'): ?>
                <!-- Вкладка: Все сотрудники -->
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'position_type')->radioList($model->getPositionTypeList()) ?>
                        <?= $form->field($model, 'department_id')->dropDownList($model->getDepartmentsList(), 
                            ['prompt' => 'Все отделы']
                        ) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->render('_common-filters', ['model' => $model, 'form' => $form]) ?>
                    </div>
                </div>

            <?php elseif ($activeTab === 'teams'): ?>
                <!-- Вкладка: Сотрудники в бригадах -->
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'team_id')->dropDownList(
                            $model->getTeamsList(), 
                            ['prompt' => 'Все бригады']
                        ) ?>
                        <?= $form->field($model, 'department_id')->dropDownList(
                            $model->getDepartmentsList(), 
                            ['prompt' => 'Все отделы']
                        ) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->render('_common-filters', ['model' => $model, 'form' => $form]) ?>
                    </div>
                </div>

            <?php elseif ($activeTab === 'medical'): ?>
                <!-- Вкладка: Пилоты с медосмотрами -->
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'medical_result')->dropDownList(
                            $model->getMedicalResultList()
                        ) ?>
                        <?= $form->field($model, 'medical_year')->textInput([
                            'type' => 'number',
                            'min' => 2000,
                            'max' => 2030,
                            'placeholder' => 'Год медосмотра'
                        ]) ?>
                        <?= $form->field($model, 'department_id')->dropDownList(
                            $model->getDepartmentsList(), 
                            ['prompt' => 'Все отделы']
                        ) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->render('_common-filters', ['model' => $model, 'form' => $form]) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Найти сотрудников', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Сбросить', ['search', 'tab' => $activeTab], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>