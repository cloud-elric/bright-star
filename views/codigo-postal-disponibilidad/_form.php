<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntCodigoPostalDisponibilidad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ent-codigo-postal-disponibilidad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'txt_codigo_postal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'num_dia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_hora_inicial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_hora_final')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
