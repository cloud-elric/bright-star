<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\depdrop\DepDrop;
use app\models\Constantes;
use yii\web\View;
use app\modules\ModUsuarios\models\Utils;

/* @var $this yii\web\View */
/* @var $model app\models\EntCitas */
/* @var $form yii\widgets\ActiveForm */

$this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/toastr/toastr.css',
    ['depends' => [\app\assets\AppAsset::className()]]
);
$this->registerCssFile(
    '@web/webAssets/templates/classic/topbar/assets/examples/css/advanced/toastr.css',
    ['depends' => [\app\assets\AppAsset::className()]]
);

$this->registerJsFile(
    '@web/webAssets/templates/classic/global/vendor/toastr/toastr.js',
    ['depends' => [\app\assets\AppAsset::className()]]
);
$this->registerJsFile(
    '@web/webAssets/templates/classic/global/js/Plugin/toastr.js',
    ['depends' => [\app\assets\AppAsset::className()]]
);

$equipo = $model->idEquipo;
$cat = $model->idCat;
?>

    <?php $form = ActiveForm::begin([
        'errorCssClass'=>"has-danger",
        'id'=>'form-cita',
        'fieldConfig' => [
            "labelOptions" => [
                "class" => "form-control-label"
            ]
        ]
    ]); ?>


<div class="citas-cont">
    <div class="row">
        <div class="col-md-12">
            <h5 class="panel-title">Datos generales</h5>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?php 
            if($model->isNewRecord){
                echo $form->field($model, 'txt_telefono')->textInput(['maxlength' => true, 'class'=>'form-control input-number' ]); 
            }else{
            ?>
            <div class="form-group">
                <label class="form-control-label" for="telefono">Télefono</label>
                <?=Html::textInput("telefono", $model->txt_telefono, ["class"=>"form-control", 'disabled'=>true])?>
                <div class="help-block"></div>
            </div>
            <?php
            }    
            ?>
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_nombre')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_apellido_paterno')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_apellido_materno')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?php 
                $end = date('Y-m-d', strtotime('-12 years'));
                $end = Utils::changeFormatDate($end);
                echo $form->field($model, 'fch_nacimiento')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => '16-12-1990'],
                    'pickerButton'=>false,
                    'removeButton'=>false,
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-mm-yyyy',
                        'endDate'=> $end,
                    ]
                ]);
            ?>
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_rfc')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'isEdicion')->hiddenInput(['maxlength' => true])->label(false) ?>
            <?= $form->field($model, 'fch_creacion')->hiddenInput(['maxlength' => true])->label(false) ?>
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_tpv')->textInput(['maxlength' => true, "class"=>"form-control input-number-decimal"]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'id_tipo_tramite')
                            ->widget(Select2::classname(), [
                                'data' => ArrayHelper::map($tiposTramites, 'id_tramite', 'txt_nombre'),
                                'language' => 'es',
                                'options' => ['placeholder' => 'Seleccionar tipo de trámite'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
            ?>                
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'id_tipo_cliente')
                                ->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map($tiposClientes, 'id_tipo_cliente', 'txt_nombre'),
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'Seleccionar tipo de cliente'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                ?>  
        </div>
        <div class="col-sm-3 col-md-3">
        
        <?= $form->field($model, 'txt_equipo')->textInput(['maxlength' => true]) ?>
        
        <?php
             require(__DIR__ . '/../components/select2.php');
            // $url = Url::to(['equipos/buscar-equipo']);
            // $valEquipo = empty($model->id_equipo) ? '' : $equipo->txt_nombre;
            // //$equipo = empty($model->id_equipo) ? '' : CatEquipos::findOne($model->id_equipo)->txt_nombre;
            // // render your widget
            // echo $form->field($model, 'id_equipo')->widget(Select2::classname(), [
            //     //'initValueText' => $cityDesc,
            //     'options' => ['placeholder' => 'Seleccionar equipo'],
            //     'pluginOptions' => [
            //         //'allowClear' => true,
            //         'minimumInputLength' => 1,
            //         'ajax' => [
            //             'url' => $url,
            //             'dataType' => 'json',
            //             'delay' => 250,
            //             'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
            //             'processResults' => new JsExpression($resultsJs),
            //             'cache' => true
            //         ],
            //         'escapeMarkup' => new JsExpression('function (markup) { if(!markup){
            //             return "Selecciona equipo";
            //         }return markup; }'),
            //         'templateResult' => new JsExpression('formatRepoEquipo'),
            //         'templateSelection' => new JsExpression('function (equipo) { 
            //             if(equipo.txt_nombre){
            //                 return equipo.txt_nombre; 
            //             }else{
            //                 return "'.$valEquipo.'"
            //             } }'),
            //     ],
            // ]);
        
        ?>  

            <div>

            </div>     
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_sap_equipo')->textInput(['maxlength' => true, 'class'=>'form-control']) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_sap_iccid')->textInput(['maxlength' => true, "autocomplete"=>"off"]) ?>
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_iccid')->textInput(['maxlength' => true, "autocomplete"=>"off"]) ?>
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_imei')->textInput(['maxlength' => true, "autocomplete"=>"off"]) ?>
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_numero_telefonico_nuevo')->textInput(['maxlength' => true, 'class'=>'form-control input-number']) ?>
        </div>
    </div>
    <div class="row">
        
        <div class="col-sm-3 col-md-3">
            
            <?= $form->field($model, 'b_documentos', [
                    'template'=>"{input}{label}{error}",
                    'options' => [
                        'class' => 'checkbox-custom checkbox-primary',
                        
                        ]
                    ])->checkbox([], false) ?>
        </div>
        <div class="col-sm-3 col-md-3">
            
            <?= $form->field($model, 'b_promocionales', [
                    'template'=>"{input}{label}{error}",
                    'options' => [
                        'class' => 'checkbox-custom checkbox-primary',
                        
                        ]
                    ])->checkbox([], false)  ?>
        </div>
        
        <div class="col-sm-3 col-md-3">
            
            <?= $form->field($model, 'b_sim', [
                    'template'=>"{input}{label}{error}",
                    'options' => [
                        'class' => 'checkbox-custom checkbox-primary',
                        
                        ]
                    ])->checkbox([], false) ?>
        </div>
    </div>

    <div class="row contenedor-promocionales">
        <div class="col-md-3">
            <?= $form->field($model, 'txt_promocional')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'txt_sap_promocional')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'txt_promocional_2')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'txt_sap_promocional_2')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'txt_promocional_3')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'txt_sap_promocional_3')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'txt_promocional_4')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'txt_sap_promocional_4')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'txt_promocional_5')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'txt_sap_promocional_5')->textInput() ?>
        </div>
    </div>


</div>

<div class="citas-cont">
    <div class="row">
        <div class="col-md-12">
            <h5 class="panel-title">Datos de contacto</h5>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'b_entrega_cat', [
                    'template'=>"{input}{label}{error}",
                    'options' => [
                        'class' => 'checkbox-custom checkbox-primary',
                        
                        ]
                    ])->checkbox([], false) ?>
        </div>
        <div class="col-sm-6 col-md-6 contenedor-cat">
            <?php
            $url = Url::to(['cats/buscar-cat']);
            $valCat = empty($model->id_cat) ? '' : $cat->txt_nombre;
            //$equipo = empty($model->id_equipo) ? '' : CatEquipos::findOne($model->id_equipo)->txt_nombre;
            // render your widget
            echo $form->field($model, 'id_cat')->widget(Select2::classname(), [
                'options' => ['placeholder' => 'Selecciona CAC'],
                
                'pluginOptions' => [
                    
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
                    'escapeMarkup' => new JsExpression('function (markup) { 
                        
                        if(!markup){
                            return "Selecciona CAC";
                        }
                        return markup; }'),
                    'templateResult' => new JsExpression('formatRepoCat'),
                    'templateSelection' => new JsExpression('function (equipo) { 
                        
                        if(equipo.id){
                            habilitarCamposDireccion();
                            colocarCamposDireccion(equipo);
                            if ($("#entcitas-txt_codigo_postal").data("select2")) {
                                $("#entcitas-txt_codigo_postal").select2("trigger", "select", {
                                    data: { id: equipo.txt_codigo_postal, txt_nombre:equipo.txt_codigo_postal }
                                });
                            }    
                        }else{
                            colocarCamposDireccionPredeterminados();
                            deshabilitarCamposDireccion();
                            limpiarCamposDireccion();
                            
                        }

                        if(equipo.txt_nombre){
                            return equipo.txt_nombre; 
                        }else if(equipo.text && !equipo.id){
                            return equipo.text;
                        }else{
                            return "'.$valCat.'"
                        } 
                    }'),
                ],
            ])->label(false);
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?php
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
              
        </div>
        <div class="col-sm-6 col-md-6">
            <?= $form->field($model, 'txt_calle_numero')->textInput(['maxlength' => true]) ?>
        </div>
            
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-4">
            <?= $form->field($model, 'txt_colonia')->textInput(['maxlength' => true]) ?>
        </div>    
        <div class="col-sm-4 col-md-4">
        
            <?= $form->field($model, 'txt_municipio')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4 col-md-4">
            <?= $form->field($model, 'txt_estado')->textInput(['maxlength' => true]) ?>
        </div>
        
    </div>

    <div class="row js-puntos-referencias">
        <div class="col-sm-6 col-md-6">
            <?= $form->field($model, 'txt_entre_calles')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6 col-md-6">
            <?= $form->field($model, 'txt_observaciones_punto_referencia')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_numero_referencia')->textInput(['maxlength' => true, 'class'=>'form-control input-number']) ?>
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_numero_referencia_2')->textInput(['maxlength' => true, 'class'=>'form-control input-number']) ?>
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_numero_referencia_3')->textInput(['maxlength' => true, 'class'=>'form-control input-number']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'id_tipo_identificacion')
                                    ->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map($tiposIdentificaciones, 'id_tipo_identificacion', 'txt_nombre'),
                                        'language' => 'es',
                                        'options' => ['placeholder' => 'Seleccionar identificación'],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]);
                ?>  
        </div>
        <div class="col-sm-3 col-md-3">
            <?= $form->field($model, 'txt_folio_identificacion')->textInput(['maxlength' => true]) ?>        
        </div>
    </div>
</div>

<div class="citas-cont pb-20">
    <div class="row">
        <div class="col-md-12">
            <h5 class="panel-title">Datos de la cita</h5>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3 col-md-3">
            <div class="form-group">
                <?php
                $areaText = $model->id_area?$model->idArea->txt_nombre:'';
                ?>
                <?=Html::label("Área", "txt_area", ["class"=>"form-control-label"])?>
                <?=Html::textInput("txt_area", $areaText, ['class'=>'form-control', 'disabled'=>'disabled', 'id'=>'txt_area' ])?>
            </div>    
                <?= $form->field($model, 'id_area')->hiddenInput(['maxlength' => true])->label(false) ?>
              
        </div>
        <div class="col-sm-3 col-md-3">
            <div class="form-group">
                <?=Html::label("Días de servicio", "num_dias_servicio", ["class"=>"form-control-label"])?>
                <?=Html::textInput("num_dias_servicio", $model->num_dias_servicio, ['class'=>'form-control', 'disabled'=>'disabled', 'id'=>'num_dias_servicio' ])?>
            </div>    
                <?= $form->field($model, 'num_dias_servicio')->hiddenInput(['maxlength' => true])->label(false) ?>
        </div>
        <div class="col-sm-3 col-md-3">
            <?php
            $startDate = $model->fch_cita;
            $end = date('Y-m-d', strtotime('+2 months'));
            $end = Utils::changeFormatDate($end);
            //$model->fch_cita = null;
            echo $form->field($model, 'fch_cita')->widget(DatePicker::classname(), [
                //'options' => ['placeholder' => '16/12/1990'],
                'pickerButton'=>false,
                'removeButton'=>false,
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd-mm-yyyy',
                    'daysOfWeekDisabled'=> "0,",
                    'startDate' => $startDate, //date("d-m-Y")
                    'endDate'=>$end
                ]
                // 'language' => 'es',
                // 'options'=>['class'=>'form-control'],
                // 'dateFormat' => 'dd-MM-yyyy',
                // 'clientOptions' => [
                //     'minDate' => $tresDias, //date("d-m-Y")
                //     'dayNamesShort' => ['Dom', 'Lun', 'Mar', 'Mié;', 'Juv', 'Vie', 'Sáb'],
                //     'dayNamesMin' => ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
                //     'beforeShowDay' => false             
                // ],
            ])
            ?>
        </div>
        
        <div class="col-sm-3 col-md-3 horario-area" > 
            <?php
    
            echo $form->field($model, 'id_horario')->widget(DepDrop::classname(), [
                
                'options' => ['placeholder' => 'Seleccionar ...'],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options'=>[
                    'pluginOptions'=>[
                        
                        'allowClear'=>true,
                        'escapeMarkup' => new JsExpression('function (markup) { 
                            console.log();
                            return markup; }'),
                        'templateResult' => new JsExpression('formatRepo'),
                    ],
                    ],
                'pluginOptions'=>[
                    'initialize' => true,
                    'url' => Url::to(['/horarios-areas/get-horarios-disponibilidad-by-area?horario='.$model->id_horario]),
                    'depends'=>['entcitas-fch_cita', 'entcitas-id_area'],
                    'params'=>[
                        'entcitas-id_area',
                        'entcitas-fch_cita',
                        'entcitas-fch_creacion',
                        'entcitas-txt_codigo_postal',
                    ],  
                    'loadingText' => 'Cargando horarios ...',
                    
                ]
                
            ]);
        ?>
        </div>

        <div class="col-sm-3 col-md-3 horario-cac"> 
            <?php
    
            echo $form->field($model, 'txt_horario_entrega_cat')->widget(DepDrop::classname(), [
                
                'options' => ['placeholder' => 'Seleccionar ...'],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options'=>[
                    'pluginOptions'=>[
                        
                        'allowClear'=>true,
                        'escapeMarkup' => new JsExpression('function (markup) { 
                            
                            return markup; }'),
                        'templateResult' => new JsExpression('formatRepo'),
                    ],
                    ],
                'pluginOptions'=>[
                    'initialize' => true,
                    'url' => Url::to(['/horarios-areas/get-horarios-disponibilidad-by-cac?cac='.$model->txt_horario_entrega_cat]),
                    'depends'=>['entcitas-id_cat', 'entcitas-fch_cita'],
                    'params'=>[
                        'entcitas-id_cat',
                        'entcitas-fch_cita',

                    ],  
                    'loadingText' => 'Cargando horarios ...',
                    
                ]
                
            ]);
        ?>
        </div>
    </div>
</div>

<?php
if($model->isNewRecord){
?>
<div class="citas-cont">
    <div class="form-group">
        <?= $model->getBotonGuardar() ?>
    </div>
</div>
<?php
}
?>
<?php ActiveForm::end(); ?>


<?php
$this->registerJs(
  '
    var codigoPostal = "'. trim(preg_replace('/\s+/', ' ',addslashes($model->txt_codigo_postal))).'";
    var calleYNumbero = "'. trim(preg_replace('/\s+/', ' ',addslashes($model->txt_calle_numero))).'";
    var colonia = "'. trim(preg_replace('/\s+/', ' ',addslashes($model->txt_colonia))).'";
    var municipio = "'. trim(preg_replace('/\s+/', ' ',addslashes($model->txt_municipio))).'";
    var estado = "'. trim(preg_replace('/\s+/', ' ',addslashes($model->txt_estado))).'";
    var entreCalles = "'. trim(preg_replace('/\s+/', ' ',addslashes($model->txt_entre_calles))).'";
    var pReferencias = "'. trim(preg_replace('/\s+/', ' ',addslashes($model->txt_observaciones_punto_referencia))).'";
  ',
  View::POS_BEGIN,
  'variables'
);
?>
    <?php
$this->registerJs(
  '

  $("#entcitas-txt_numero_referencia").on("blur", function(){
    var valor = $(this).val();
    var comparar1 = $("#entcitas-txt_numero_referencia_2").val();
    var comparar2 = $("#entcitas-txt_numero_referencia_3").val();
    if(valor){
        if(valor == comparar1 || valor==comparar2){
            
            $("#form-cita").yiiActiveForm("updateAttribute", "entcitas-txt_numero_referencia", ["No puede repetir el número de referencia"]);
        }
    }
  });

  $("#entcitas-txt_numero_referencia_2").on("blur", function(){
        var valor = $(this).val();
        var comparar1 = $("#entcitas-txt_numero_referencia_1").val();
        var comparar2 = $("#entcitas-txt_numero_referencia_3").val();
        if(valor){
            if(valor == comparar1 || valor==comparar2){
                
                $("#form-cita").yiiActiveForm("updateAttribute", "entcitas-txt_numero_referencia_2", ["No puede repetir el número de referencia"]);
            }
        }
    });

    $("#entcitas-txt_numero_referencia_3").on("blur", function(){
        var valor = $(this).val();
        var comparar1 = $("#entcitas-txt_numero_referencia_1").val();
        var comparar2 = $("#entcitas-txt_numero_referencia_2").val();
        if(valor){
            if(valor == comparar1 || valor==comparar2){
               
                $("#form-cita").yiiActiveForm("updateAttribute", "entcitas-txt_numero_referencia_3", ["No puede repetir el número de referencia"]);
            }
        }
    });  

  $("#entcitas-txt_telefono").on("change", function(){
    var elemento = $(this);
    var data = elemento.val();
    if(elemento.val().length==10){
        $.ajax({
            url: baseUrl+"citas/validar-telefono?tel="+data,
            success:function(res){

                if(res.status=="error"){
                    swal({
                        title: "Datos no válidos",
                        text: res.mensaje,
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonClass: "btn-warning",
                        confirmButtonText: "Ok",
                        cancelButtonText: "No, revisaré una vez más",
                        closeOnConfirm: true,
                        //closeOnCancel: false
                    });
                    elemento.val("");
                    elemento.trigger("change");
                }    
            }
        });
    }

  });

  $("#entcitas-txt_iccid").on("change", function(){
    var elemento = $(this);
    var data = elemento.val();
    
        $.ajax({
            url: baseUrl+"citas/validar-iccid?tel="+data,
            success:function(res){

                if(res.status=="error"){
                    swal({
                        title: "Datos no válidos",
                        text: res.mensaje,
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonClass: "btn-warning",
                        confirmButtonText: "Ok",
                        cancelButtonText: "No, revisaré una vez más",
                        closeOnConfirm: true,
                        //closeOnCancel: false
                    });
                    elemento.val("");
                    elemento.trigger("change");
                }    
            }
        });
    

  });

  $("#entcitas-txt_imei").on("change", function(){
    var elemento = $(this);
    var data = elemento.val();
    
        $.ajax({
            url: baseUrl+"citas/validar-imei?tel="+data,
            success:function(res){

                if(res.status=="error"){
                    swal({
                        title: "Datos no válidos",
                        text: res.mensaje,
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonClass: "btn-warning",
                        confirmButtonText: "Ok",
                        cancelButtonText: "No, revisaré una vez más",
                        closeOnConfirm: true,
                        //closeOnCancel: false
                    });
                    elemento.val("");
                    elemento.trigger("change");
                }    
            }
        });
    

  });

  var claseOcultar = "hidden-xl-down";
  checkStatus();
  checkPromocionales();
  checkIsCat();
  
function checkStatus(){
    var val = $("#entcitas-id_equipo").val();
    
    if(val=="'.Constantes::SIN_EQUIPO.'"){
        $("#entcitas-b_documentos").prop("checked", true);
        $("#entcitas-b_documentos").attr("disabled", true);
    }else{
        $("#entcitas-b_documentos").prop("checked", false);
        $("#entcitas-b_documentos").attr("disabled", false);
    }
}


function checkPromocionales(){
    if($("#entcitas-b_promocionales").prop("checked")){
        $(".contenedor-promocionales").show();
    }else{
        $(".contenedor-promocionales").hide();
    }

}

function checkIsCat(){
    if($("#entcitas-b_entrega_cat").prop("checked")){
        $(".contenedor-cat").show();
        $(".js-puntos-referencias").hide();
        habilitarCamposDireccion();
        mostrarHorarioCac();
    }else{
        ocultarHorarioCac();
        $("#entcitas-id_cat").select2("val", "");
        $(".contenedor-cat").hide();
        $(".js-puntos-referencias").show();
        
        deshabilitarCamposDireccion();
        limpiarCamposDireccion();
        colocarCamposDireccionPredeterminados();
    }
}

function mostrarHorarioCac(){
    $(".horario-cac").show();
    $(".horario-area").hide();
}

function ocultarHorarioCac(){
    $(".horario-cac").hide();
    $(".horario-area").show();
}

$("#entcitas-b_entrega_cat").on("change", function(){
   
    checkIsCat();
});

$("#entcitas-b_promocionales").on("change", function(){
    checkPromocionales();
});
  
  $("#entcitas-id_equipo").on("change", function(){
    checkStatus();

  });

  $("#form-cita").on("beforeSubmit", function(){
      if($("#js-guardar-cita-btn").get(0)){
        var l = Ladda.create($("#js-guardar-cita-btn").get(0));
        l.start();
      }
  });

  $("#form-cita").on("afterValidate", function (e, attr, messages) {
    if(messages.length>0){
        toastr.options = {
            "closeButton": true,
            
            "positionClass": "toast-top-full-width",
            "preventDuplicates": true,
            
            "showDuration": "3000",
            "hideDuration": "10000",
            "timeOut": "5000",
            "extendedTimeOut": "10000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
          }
        toastr.warning("La cita tiene datos incorrectos. Por favor revisar")
    }
});
  ',
  View::POS_READY,
  'tipo-usuario'
);
?>