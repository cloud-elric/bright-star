<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntCodigoPostalDisponibilidad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ent-codigo-postal-disponibilidad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_municipio')->textInput() ?>

    <?= $form->field($model, 'id_area')->textInput() ?>

    <?= $form->field($model, 'id_horario')->textInput() ?>

    <?= $form->field($model, 'txt_codigo_postal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'b_habilitado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
