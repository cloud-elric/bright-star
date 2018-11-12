<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use app\models\EntHorariosAreas;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntCodigoPostalDisponibilidadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Disponibilidad C.P';
$this->params['classBody'] = "site-navbar-small page-user";

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
$this->registerJsFile(
  '@web/webAssets/js/disponibilidades-cp/index.js',
  ['depends' => [\app\assets\AppAsset::className()]]
);

require(__DIR__ . '/../components/select2.php');
$url = Url::to(['codigo-postal-disponibilidad/buscar-municipio']);
$selectMunicipio =  Select2::widget([
  
  'name'=>"EntCodigoPostalDisponibilidadSearch[id_municipio]",
  'options' => ['placeholder' => 'Ingresar Municipio', "class"=>"js-filtro-municipio"],
  
  'pluginOptions' => [
      'language' => [
          'errorLoading' => new JsExpression("function () { return 'Error al cargar'; }"),
          'loadingMore' => new JsExpression("function () { return 'Cargando más resultados...'; }"),
          'noResults' => new JsExpression("function () { return 'No existen datos coincidentes'; }"),
          'searching' => new JsExpression("function () { return 'Buscando...'; }"),
          'inputTooShort' => new JsExpression(
              'function (e) { 
                  var t = e.minimum - e.input.length;
                  n = "Ingrese " + t + " o más caracteres";
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
                      
                      
                  }
      }'),
  ],
]);

$urlArea = Url::to(['codigo-postal-disponibilidad/buscar-area']);
$selectArea =  Select2::widget([
  
  'name'=>"EntCodigoPostalDisponibilidadSearch[id_area]",
  'options' => ['placeholder' => 'Ingresar area', "class"=>"js-filtro-area"],
  
  'pluginOptions' => [
      'language' => [
          'errorLoading' => new JsExpression("function () { return 'Error al cargar'; }"),
          'loadingMore' => new JsExpression("function () { return 'Cargando más resultados...'; }"),
          'noResults' => new JsExpression("function () { return 'No existen datos coincidentes'; }"),
          'searching' => new JsExpression("function () { return 'Buscando...'; }"),
          'inputTooShort' => new JsExpression(
              'function (e) { 
                  var t = e.minimum - e.input.length;
                  n = "Ingrese " + t + " o más caracteres";
                  return n; 
              }'),
      ],
      'allowClear' => true,
      'minimumInputLength' => 1,
      'ajax' => [
          'url' => $urlArea,
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
                      
                      
                  }
      }'),
  ],
]);

?>
<div class="panel panel-list-user-table">
        <?php
        
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'=>$searchModel,
            'options' => [
              'class'=>"panel-table-int"
            ],
            'responsive'=>true,
            'striped'=>false,
            'hover'=>false,
            'bordered'=>false,
            'pjax'=>true,
            'pjaxSettings'=>[
              'options'=>[
                'id'=>"pjax-disponibilidades-cp",
                'linkSelector'=>"a:not(.no-pjax)"
              ]
            ],
            'tableOptions' => [
              'class'=>"table table-hover"
            ],
            'layout' => '{items}{summary}{pager}',
                'columns' =>[
                  [
                    'attribute'=>'id_area', 
                    'filter'=>$selectArea,
                    'format'=>'raw',
                    'value'=>function($data){
                      return $data->idArea->txt_nombre;
                    },
                  ],
                    [
                      'attribute'=>'id_municipio', 
                      'filter'=>$selectMunicipio,
                      'format'=>'raw',
                      'value'=>function($data){
                        return $data->idMunicipio->txt_nombre;
                      },
                    ],
                    'txt_codigo_postal',
                    [
                      'filter'=>EntHorariosAreas::DIAS,
                      'attribute'=>"num_dia",
                      'format'=>"raw",
                      "value"=>function($data){
                        return $data->idHorario->dia;
                      }
                    ],
                    [
                      'attribute'=>"id_horario",
                      'format'=>"raw",
                      "value"=>function($data){
                        return $data->idHorario->horarioConcat;
                      }
                    ],
                    [
                        'attribute' => 'b_habilitado',
                        'filter'=>["0"=>"Inactivo", "1"=>"Activo"],
                        'format'=>'raw',
                        
                        'value'=>function($data){
            
                        $activo = $data->b_habilitado == 1?'active':'';
                        $inactivo = $data->b_habilitado == 0?'active':'';
                            
                          return '<div class="btn-group" data-toggle="buttons" role="group">
                          <label class="btn btn-active '.$activo.'"  data-token="'.$data->id_disponiblidad.'">
                          <input class="js-activar-horario" type="radio" name="options" autocomplete="off" value="male" checked />
                          Activo
                          </label>
                          <label class="btn btn-inactive '.$inactivo.'" data-token="'.$data->id_disponiblidad.'">
                          <input class="js-bloquear-desactivar"  type="radio" name="options" autocomplete="off" value="female" />
                          Inactivo
                          </label>
                          </div>';
                        }
                      ],
                ],
                'panelTemplate' => "{panelHeading}\n{items}\n{summary}\n{pager}",
                //"panelHeadingTemplate"=>"{export}",
                "panelHeadingTemplate"=>"",
                'responsive'=>true,
                'striped'=>false,
                'hover'=>false,
                'bordered'=>false,
                'panel' => [
                    'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Countries</h3>',
                    'type'=>'success',
                    'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create Country', ['create'], ['class' => 'btn btn-success']),
                    'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
                    'footer'=>false
                ],
               
                'pager'=>[
                    'linkOptions' => [
                        'class' => 'page-link'
                    ],
                    'pageCssClass'=>'page-item',
                    'prevPageCssClass' => 'page-item',
                    'nextPageCssClass' => 'page-item',
                    'firstPageCssClass' => 'page-item',
                    'lastPageCssClass' => 'page-item',
                    'maxButtonCount' => '5',
                ]
            ]) ?>
</div> 
