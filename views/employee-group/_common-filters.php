<?php

use yii\helpers\Html;

?>
<div class="common-filters">
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'min_experience')->textInput([
                'type' => 'number', 
                'min' => 0,
                'placeholder' => 'Мин. стаж'
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'max_experience')->textInput([
                'type' => 'number', 
                'min' => 0,
                'placeholder' => 'Макс. стаж'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'min_age')->textInput([
                'type' => 'number', 
                'min' => 18,
                'max' => 100,
                'placeholder' => 'Мин. возраст'
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'max_age')->textInput([
                'type' => 'number', 
                'min' => 18,
                'max' => 100,
                'placeholder' => 'Макс. возраст'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'min_salary')->textInput([
                'type' => 'number', 
                'min' => 0,
                'step' => 0.01,
                'placeholder' => 'Мин. зарплата'
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'max_salary')->textInput([
                'type' => 'number', 
                'min' => 0,
                'step' => 0.01,
                'placeholder' => 'Макс. зарплата'
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'gender')->dropDownList(
        $model->getGenderList(), 
        ['prompt' => 'Любой пол']
    ) ?>

    <?= $form->field($model, 'has_children')->dropDownList(
        $model->getHasChildrenList(), 
        ['prompt' => 'Не важно']
    ) ?>

    <?= $form->field($model, 'children_count')->textInput([
        'type' => 'number', 
        'min' => 0,
        'placeholder' => 'Любое количество'
    ]) ?>
</div>