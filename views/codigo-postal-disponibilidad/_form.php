<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\EntCodigoPostalDisponibilidad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ent-codigo-postal-disponibilidad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    require(__DIR__ . '/../components/select2.php');
    $url = Url::to(['codigos-postales/buscar-codigo']);
    $valCat = $model->txt_codigo_postal;
    //$equipo = empty($model->id_equipo) ? '' : CatEquipos::findOne($model->id_equipo)->txt_nombre;
    // render your widget
    echo $form->field($model, 'txt_codigo_postal')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Ingresar CP'],
        
        'pluginOptions' => [
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Error al cargar'; }"),
                'loadingMore' => new JsExpression("function () { return 'Cargando más resultados...'; }"),
                'noResults' => new JsExpression("function () { return 'No existe cobertura para el C.P. ingresado'; }"),
                'searching' => new JsExpression("function () { return 'Buscando...'; }"),
                'inputTooShort' => new JsExpression(
                    'function (e) { 
                        var t = e.minimum - e.input.length;
                        n = "Ingrese " + t + " o más dígitos";
                        return n; 
                    }'),
            ],
            'allowClear' => true,
            'minimumInputLength' => 1,
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'delay' => 250,
                'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                'processResults' => new JsExpression($resultsJs),
                'cache' => true
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(equipo) { return equipo.txt_nombre; }'),
            'templateSelection' => new JsExpression('function (equipo) { 
                console.log(equipo);
                        if(equipo.txt_nombre){
                            return equipo.txt_nombre;
                        }else{
                            
                            return "'.$model->txt_codigo_postal.'"
                        }
            }'),
        ],
    ])?>

    <?= $form->field($model, 'num_dia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_hora_inicial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_hora_final')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
