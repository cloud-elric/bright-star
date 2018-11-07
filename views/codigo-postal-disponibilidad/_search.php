<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntCodigoPostalDisponibilidadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ent-codigo-postal-disponibilidad-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_disponiblidad') ?>

    <?= $form->field($model, 'txt_codigo_postal') ?>

    <?= $form->field($model, 'num_dia') ?>

    <?= $form->field($model, 'txt_hora_inicial') ?>

    <?= $form->field($model, 'txt_hora_final') ?>

    <?php // echo $form->field($model, 'b_habilitado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
