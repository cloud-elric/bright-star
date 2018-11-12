<?php
use kartik\export\ExportMenu;
use app\models\Calendario;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = 'Exportar datos';
$this->params['classBody'] = "site-navbar-small site-menubar-hide";
$startDate = Calendario::changeFormatDateInputShortStart(Calendario::getFechaActual());

$this->registerJsFile(
    '@web/webAssets/js/citas/exportar.js',
    ['depends' => [\app\assets\AppAsset::className()]]
);
?>

<style>
    .kv-container-from.form-control.field-entcitassearch-startdate, .kv-container-to.form-control.field-entcitassearch-enddate{
        padding:0;
    }
</style>

<div class="panel-citas-create">
    <?php $form = ActiveForm::begin([
        'errorCssClass'=>"has-danger",
        'action'=>'download-data',
        'options'=>[
            'target'=>"_blank",
            "autocomplete"=>"off"
           ],
        'method'=>"GET",
        'id'=>'form-search',
        'fieldConfig' => [
            "labelOptions" => [
                "class" => "form-control-label"
            ]
        ]
    ]); ?>

    <div class="citas-cont">
        <div class="row">
            <div class="col-md-12">
                <h5 class="panel-title">Exportar reporte de día (1 día seleccionado, por fecha de cita)</h5>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">

                <div class="form-group">
                    <?php
                    // Usage with model and Active Form (with no default initial value)
                    echo $form->field($modelSearch, 'fch_cita')->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'Fecha cita'],
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [

                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            
                            'maxViewMode'=>2
                        ],
                    ]);
                   
                    ?>
                </div>    
            </div>
            <div class="col-md-3">
                
            </div>
        </div>    
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton('Buscar', ['class' => 'btn btn-success', 'name'=>'isOpen', 'value'=>Yii::$app->request->get('isOpen')?'1':'0']) ?>
                    <?= Html::button('Limpiar', ['class' => 'btn btn-primary limpiar-busqueda']) ?>
                </div>
            </div>    
        </div>    
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="panel-citas-create">
    <?php $form = ActiveForm::begin([
        'errorCssClass'=>"has-danger",
       'options'=>[
        'target'=>"_blank",
        "autocomplete"=>"off"
       ],
        'action'=>'download-data',
        'method'=>"GET",
        'id'=>'form-search-2',
        'fieldConfig' => [
            "labelOptions" => [
                "class" => "form-control-label"
            ]
        ]
    ]); ?>

    <div class="citas-cont">
        <div class="row">
            <div class="col-md-12">
                <h5 class="panel-title">Exportar citas capturadas por semana (7 días atrás desde la fecha seleccionada, por fecha de creación)</h5>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">

                <div class="form-group">
                    <?php
                    // Usage with model and Active Form (with no default initial value)
                    echo $form->field($modelSearch, 'fch_creacion')->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'Fecha de creación'],
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [

                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            
                            'maxViewMode'=>2
                        ],
                    ]);
                   
                    ?>
                </div>    
            </div>
            <div class="col-md-3">
                
            </div>
        </div>    
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton('Buscar', ['class' => 'btn btn-success', 'name'=>'isOpen', 'value'=>Yii::$app->request->get('isOpen')?'1':'0']) ?>
                    <?= Html::button('Limpiar', ['class' => 'btn btn-primary limpiar-busqueda']) ?>
                </div>
            </div>    
        </div>    
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="panel-citas-create">
    <?php $form = ActiveForm::begin([
        'errorCssClass'=>"has-danger",
       'options'=>[
        'target'=>"_blank",
        "autocomplete"=>"off"
       ],
        'action'=>'download-data',
        'method'=>"GET",
        'id'=>'form-search-3',
        'fieldConfig' => [
            "labelOptions" => [
                "class" => "form-control-label"
            ]
        ]
    ]); ?>

    <div class="citas-cont">
        <div class="row">
            <div class="col-md-12">
                <h5 class="panel-title">Exportar citas por rango de fecha (Por fecha de creación)</h5>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">

                <div class="form-group">
                    <?php
                    // Usage with model and Active Form (with no default initial value)
                    echo DatePicker::widget([
                        'model' => $modelSearch,
                        'attribute' => 'startDate',
                        'attribute2' => 'endDate',
                        'options' => ['placeholder' => 'Fecha inicio'],
                        'options2' => ['placeholder' => 'Fecha final'],
                        
                        'type' => DatePicker::TYPE_RANGE,
                        'form' => $form,
                        'separator' => '<i class="icon  fa-arrows-h"></i>',
                        
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            'startDate'=> $startDate,
                            'maxViewMode'=>2
                        ],
                       ]);
                   
                    ?>
                </div>    
            </div>
            <div class="col-md-3">
                
            </div>
        </div>    
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton('Buscar', ['class' => 'btn btn-success', 'name'=>'isOpen', 'value'=>Yii::$app->request->get('isOpen')?'1':'0']) ?>
                    <?= Html::button('Limpiar', ['class' => 'btn btn-primary limpiar-busqueda']) ?>
                </div>
            </div>    
        </div>    
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="panel-citas-create">
    <?php $form = ActiveForm::begin([
        'errorCssClass'=>"has-danger",
       'options'=>[
        'target'=>"_blank",
        "autocomplete"=>"off"
       ],
        'action'=>'download-data',
        'method'=>"GET",
        'id'=>'form-search-4',
        'fieldConfig' => [
            "labelOptions" => [
                "class" => "form-control-label"
            ]
        ]
    ]); ?>

    <div class="citas-cont">
        <div class="row">
            <div class="col-md-12">
                <h5 class="panel-title">Exportar citas por rango de fecha (Por fecha de cita)</h5>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">

                <div class="form-group">
                    <?php
                    // Usage with model and Active Form (with no default initial value)
                    echo DatePicker::widget([
                        'model' => $modelSearch,
                        'attribute' => 'startDateCita',
                        'attribute2' => 'endDateCita',
                        'options' => ['placeholder' => 'Fecha inicio'],
                        'options2' => ['placeholder' => 'Fecha final'],
                        
                        'type' => DatePicker::TYPE_RANGE,
                        'form' => $form,
                        'separator' => '<i class="icon  fa-arrows-h"></i>',
                        
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            'startDate'=> $startDate,
                            'maxViewMode'=>2
                        ],
                       ]);
                   
                    ?>
                </div>    
            </div>
            <div class="col-md-3">
                
            </div>
        </div>    
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton('Buscar', ['class' => 'btn btn-success', 'name'=>'isOpen', 'value'=>Yii::$app->request->get('isOpen')?'1':'0']) ?>
                    <?= Html::button('Limpiar', ['class' => 'btn btn-primary', "id"=>"limpiar-busqueda"]) ?>
                </div>
            </div>    
        </div>    
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="panel-citas-create">
    <?php $form = ActiveForm::begin([
        'errorCssClass'=>"has-danger",
       'options'=>[
        'target'=>"_blank",
        "autocomplete"=>"off"
       ],
        'action'=>'download-data-citas',
        'method'=>"GET",
        'id'=>'form-search-5',
        'fieldConfig' => [
            "labelOptions" => [
                "class" => "form-control-label"
            ]
        ]
    ]); ?>

    <div class="citas-cont">
        <div class="row">
            <div class="col-md-12">
                <h5 class="panel-title">Exportar estatus de citas por rango de fecha (Por fecha de cita)</h5>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">

                <div class="form-group">
                    <?php
                    // Usage with model and Active Form (with no default initial value)
                    echo DatePicker::widget([
                        'model' => $modelSearch,
                        'attribute' => 'startDateCita',
                        'attribute2' => 'endDateCita',
                        'options' => ['placeholder' => 'Fecha inicio', "id"=>"new"],
                        'options2' => ['placeholder' => 'Fecha final', "id"=>"new2"],
                        
                        'type' => DatePicker::TYPE_RANGE,
                        'form' => $form,
                        'separator' => '<i class="icon  fa-arrows-h"></i>',
                        
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            'startDate'=> $startDate,
                            //'endDate'=> date('Y-m-d', strtotime()),
                            'maxViewMode'=>2
                        ],
                       ]);
                   
                    ?>
                </div>    
            </div>
            <div class="col-md-3">
                
            </div>
        </div>    
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton('Buscar', ['class' => 'btn btn-success', 'name'=>'isOpen', 'value'=>Yii::$app->request->get('isOpen')?'1':'0']) ?>
                    <?= Html::button('Limpiar', ['class' => 'btn btn-primary limpiar-busqueda']) ?>
                </div>
            </div>    
        </div>    
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="panel-citas-create">
    <?php $form = ActiveForm::begin([
        'errorCssClass'=>"has-danger",
       'options'=>[
        'target'=>"_blank",
        "autocomplete"=>"off"
       ],
        'action'=>'download-data-citas-envio',
        'method'=>"GET",
        'id'=>'form-search-6',
        'fieldConfig' => [
            "labelOptions" => [
                "class" => "form-control-label"
            ]
        ]
    ]); ?>

    <div class="citas-cont">
        <div class="row">
            <div class="col-md-12">
                <h5 class="panel-title">Exportar citas por fecha (Fecha de entrega de equipo a bright star)</h5>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">

                <div class="form-group">
                    <?php
                    // Usage with model and Active Form (with no default initial value)
                    echo DatePicker::widget([
                        'model' => $modelSearch,
                        'attribute' => 'startDateEntrega',
                        //'attribute2' => 'endDateEntrega',
                        'options' => ['placeholder' => 'Fecha de entrega', "id"=>"ne3"],
                        //'options2' => ['placeholder' => 'Fecha final', "id"=>"new4"],
                        
                        'type' => DatePicker::TYPE_INPUT,
                        'form' => $form,
                        //'separator' => '<i class="icon  fa-arrows-h"></i>',
                        
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            
                            'maxViewMode'=>2
                        ],
                       ]);
                   
                    ?>
                </div>    
            </div>
            <div class="col-md-3">
                
            </div>
        </div>    
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton('Buscar', ['class' => 'btn btn-success', 'name'=>'isOpen', 'value'=>Yii::$app->request->get('isOpen')?'1':'0']) ?>
                    <?= Html::button('Limpiar', ['class' => 'btn btn-primary limpiar-busqueda']) ?>
                </div>
            </div>    
        </div>    
    </div>
    <?php ActiveForm::end(); ?>
</div>