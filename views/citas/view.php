<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Calendario;
use app\models\EntCitas;
use yii\bootstrap\ActiveForm;
use app\models\Constantes;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\EntCitas */

$this->title = $model->txt_identificador_cliente.' <span class="badge '.EntCitas::getColorStatus($model->id_status   ).'">'.$model->idStatus->txt_nombre.'</span>';
$this->params['breadcrumbs'][] = [
    'label' => '<i class="icon pe-headphones"></i> Citas', 
    'url' => ['index'],
    'template'=>'<li class="breadcrumb-item">{link}</li>', 
    'encode' => false
];
// $this->params['breadcrumbs'][] = [
//     'label' => '<i class="icon wb-eye"></i> '.$model->txt_identificador_cliente,
//     'template'=>'<li class="breadcrumb-item">{link}</li>', 
//     'encode' => false];

$this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/bootstrap-sweetalert/sweetalert.css',
    ['depends' => [\app\assets\AppAsset::className()]]
);

$this->registerCssFile(
    '@web/webAssets/css/citas/create.css',
    ['depends' => [\app\assets\AppAsset::className()]]
);


$this->registerJsFile(
    '@web/webAssets/js/citas/create.js',
    ['depends' => [\app\assets\AppAsset::className()]]
);

$this->registerJsFile(
    '@web/webAssets/js/citas/view.js',
    ['depends' => [\app\assets\AppAsset::className()]]
);

$this->registerJsFile(
    '@web/webAssets/templates/classic/global/js/Plugin/bootstrap-sweetalert.js',
    ['depends' => [\app\assets\AppAsset::className()]]
);
?>
<input class="token-cita" type="hidden" data-token="<?=$model->txt_token?>"/>

<?php
if($model->txt_motivo_rechazo){
?>
<div class="panel-citas-view">
    <div class="citas-cont">
        <div class="row">
            <div class="col-md-12">
                <h5 class="panel-title">Motivo de rechazo</h5>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3 col-md-3">
                <?=$model->txt_motivo_rechazo?>
            </div>
        </div>
    </div>    
</div>
<?php
}
?>

<?php
if($model->txt_motivo_cancelacion_rechazo){
?>
<div class="panel-citas-view">
    <div class="citas-cont">
        <div class="row">
            <div class="col-md-12">
                <h5 class="panel-title">Motivo de cancelación</h5>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3 col-md-3">
                <?=$model->txt_motivo_cancelacion_rechazo?>
            </div>
        </div>
    </div> 
</div>    
<?php
}
?>


<div class="panel-citas-view">

    <div class="panel-citas-btns mb-20">
        <?=$model->getBotonesSupervisor()?>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'tiposTramites'=>$tiposTramites,
        'tiposClientes'=>$tiposClientes,
        'tiposIdentificaciones'=>$tiposIdentificaciones,
      
    ]) ?>
    
    <div class="panel-citas-btns">
        <?=$model->getBotonesSupervisor()?>  
    </div>

</div>

<div class="panel-citas-view">
    <div class="citas-cont">
        <h5 class="panel-title">Historial de cambios</h5>
        <hr>
    </div>
    <div class="citas-cont panel-table">
       
        <?= GridView::widget([
            'dataProvider' => $historial,
            'columns' =>[
                [
                    'attribute' => 'id_usuario',
                    'value'=>'idUsuario.txtAuthItem.description',
                    'label' => 'Tipo de usuario',
                ],
                [
                    'attribute'=>'id_usuario',
                    'value'=>'idUsuario.nombreCompleto',
                    'label' => 'Nombre usuario',
                ],
            'txt_modificacion',
            [
                    'attribute'=>'fch_modificacion',
                    'format'=>'raw',
                    'label' => 'Fecha de modificación',
                    'value'=>function($data){
        
                        return Calendario::getDateCompleteHour($data->fch_modificacion);
                    }
                ],
                
            ],
            'pjax'=>true,
            //'pjaxSettings'=>,
            'panelTemplate' => "{items}\n{summary}\n{pager}",
            //"panelHeadingTemplate"=>"<div class='pull-right'>{export}</div>",
            'responsive'=>true,
            'hover'=>true,
            'bordered'=>false,
            'striped'=>false,
            'panel' => [
                'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Countries</h3>',
                'type'=>'success',
                'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create Country', ['create'], ['class' => 'btn btn-success']),
                'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
                'footer'=>false
            ],
        
        ]) ?>
                
    </div>
</div>


<?php
if(\Yii::$app->user->can(Constantes::USUARIO_SUPERVISOR)){
    $this->registerJs(
        '

        $(document).ready(function(){
    
            $(".js-actualizar").on("click", function(e){
                e.preventDefault();
               $("#entcitas-isedicion").val(1);
                swal({
                    title: "¿Estas seguro de actualizar esta cita?",
                    text: "Se guardaran los campos modificados",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "Sí, editar cita",
                    cancelButtonText: "No, revisaré una vez más",
                    closeOnConfirm: true,
                    //closeOnCancel: false
                },
                function() {
                    
                    $("#form-cita").submit();
                    return false;
                });
            });
        });
        
        ',
        View::POS_END,
        'actualizar-cita'
        );
    
       
}

if(\Yii::$app->user->can(Constantes::USUARIO_SUPERVISOR)){
    $this->registerJs(
    '

    function validarFechaEntrega(){
        $.ajax({
            url:baseUrl+"citas/validar-fecha-entrega?token='.$model->txt_token.'",
            success:function(r){
                if(r.status=="error"){
                    swal("Espera", "La fecha de la entrega de equipo es menor al día de hoy. Actualice la fecha de la cita", "warning");
                }else{
                    swal({
                        title: "¿Estas seguro de aprobar esta cita?",
                        text: "Al autorizar se guardaran los campos modificados",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-warning",
                        confirmButtonText: "Sí, aprobar cita",
                        cancelButtonText: "No, revisaré una vez más",
                        closeOnConfirm: true,
                        //closeOnCancel: false
                    },
                    function() {
                        
                        $("#form-cita").submit();
                        return false;
                    });
                }
            },error:function(){
                swal("Esperar", "Ocurrio un error en el servidor", "error");     
            }
        });
    }

    $(document).ready(function(){

        $("#form-cita").on("afterValidate", function(e, messages, errorAttributes){
            
            if(errorAttributes.length > 0){
                swal({
                    title: "Datos no válidos",
                    text: "Hay datos no válidos en la cita",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "Revisar datos",
                    cancelButtonText: "No, revisaré una vez más",
                    closeOnConfirm: true,
                    //closeOnCancel: false
                });
                return false;
            }
        });

        $(".js-aprobar").on("click", function(e){
            e.preventDefault();
            $("#entcitas-isedicion").val(0);


            validarFechaEntrega();
            
                        
            
            
        });
    });
    
    ',
    View::POS_END,
    'aprobar-cita'
    );

    $this->registerJs(
        '
        $(".js-cancelar").on("click", function(e){
            e.preventDefault();
            $("#cita-cancelacion-modal").modal("show");
    
        })

        $(".js-rechazar").on("click", function(e){
            e.preventDefault();
            $("#cita-rechazar-modal").modal("show");
    
        })
        ',
        View::POS_END,
        'cancelar-cita'
        );

    

    $this->params['modal'] =  $this->render("_modal-cancelacion", ['model'=>$model]);

    $this->params['modal'].=  $this->render("_modal-rechazo", ['model'=>$model]);
}
?>
