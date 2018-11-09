<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\EntCodigoPostalDisponibilidad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ent-codigo-postal-disponibilidad-form">

    <?php $form = ActiveForm::begin([
        'id'=>'js-crear-disponibilidad'
    ]); ?>

    <div class="row">
        <div class="col-md-6">
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
                        if(equipo.txt_nombre){
                            return equipo.txt_nombre;
                        }else{
                            
                            return "'.$model->txt_codigo_postal.'"
                        }
                    }'),
                ],
            ])?>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-md-2 form-control-label" for="">Días:</label>
                <div class="col-md-10 col-check">
                    
                    <div class="checkbox-custom checkbox-primary">
                        <?php 
                        if($model->isNewRecord){
                        ?>
                            <input id="domingo1" name="check1" value="0" type="checkbox">
                        <?php 
                        }else{
                        ?>
                            <input id="domingo1" name="check1" value="0" type="checkbox" <?= $model->num_dia == 0 ? 'checked' : '' ?> >
                        <?php 
                        }
                        ?>
                        <label for="domingo1">D</label>
                    </div>
                    
                    <div class="checkbox-custom checkbox-primary">
                        <?php 
                        if($model->isNewRecord){
                        ?>
                            <input id="lunes2" name="check1" value="1" type="checkbox">
                        <?php 
                        }else{
                        ?>
                            <input id="lunes2" name="check1" value="1" type="checkbox" <?= $model->num_dia == 1 ? 'checked' : '' ?> >
                        <?php 
                        }
                        ?>
                        <label for="lunes2">L</label>
                    </div>

                    <div class="checkbox-custom checkbox-primary">
                        <?php 
                        if($model->isNewRecord){
                        ?>
                            <input id="martes3" name="check1" value="2" type="checkbox">
                        <?php 
                        }else{
                        ?>
                            <input id="martes3" name="check1" value="2" type="checkbox" <?= $model->num_dia == 2 ? 'checked' : '' ?> >
                        <?php 
                        }
                        ?>
                        <label for="martes3">M</label>
                    </div>

                    <div class="checkbox-custom checkbox-primary">
                        <?php 
                        if($model->isNewRecord){
                        ?>
                            <input id="miercoles4" name="check1" value="3" type="checkbox">
                        <?php 
                        }else{
                        ?>
                            <input id="miercoles4" name="check1" value="3" type="checkbox" <?= $model->num_dia == 3 ? 'checked' : '' ?> >
                        <?php 
                        }
                        ?>
                        <label for="miercoles4">M</label>
                    </div>

                    <div class="checkbox-custom checkbox-primary">
                        <?php 
                        if($model->isNewRecord){
                        ?>
                            <input id="jueves5" name="check1" value="4" type="checkbox">
                        <?php 
                        }else{
                        ?>
                            <input id="jueves5" name="check1" value="4" type="checkbox" <?= $model->num_dia == 4 ? 'checked' : '' ?> >
                        <?php 
                        }
                        ?>
                        <label for="jueves5">J</label>
                    </div>

                    <div class="checkbox-custom checkbox-primary">
                        <?php 
                        if($model->isNewRecord){
                        ?>
                            <input id="viernes6" name="check1" value="5" type="checkbox">
                        <?php 
                        }else{
                        ?>
                            <input id="viernes6" name="check1" value="5" type="checkbox" <?= $model->num_dia == 5 ? 'checked' : '' ?> >
                        <?php 
                        }
                        ?>
                        <label for="viernes6">V</label>
                    </div>

                    <div class="checkbox-custom checkbox-primary">
                        <?php 
                        if($model->isNewRecord){
                        ?>
                            <input id="sabado6" name="check1" value="6" type="checkbox">
                        <?php 
                        }else{
                        ?>
                            <input id="sabado6" name="check1" value="6" type="checkbox" <?= $model->num_dia == 6 ? 'checked' : '' ?> >
                        <?php 
                        }
                        ?>
                        <label for="sabado6">S</label>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6 input-group">
            <?= $form->field($model, 'txt_hora_inicial')->textInput(['class'=>'time', 'data-container'=>"#addNewEvent"]) ?>
            <span class="input-group-addon">
                <i class="icon wb-time" aria-hidden="true"></i>
            </span>
        </div>

        <div class="col-md-6 input-group">
            <?= $form->field($model, 'txt_hora_final')->textInput(['class'=>'time', 'data-container'=>"#addNewEvent2"]) ?>
            <span class="input-group-addon">
                <i class="icon wb-time" aria-hidden="true"></i>
            </span>
        </div>
    </div>

    <div class="form-group">
        <?= Html::button($model->isNewRecord ? 'Crear' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id'=>'js-btn-submit-disponibilidad', 'data-url'=>Url::base()]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
