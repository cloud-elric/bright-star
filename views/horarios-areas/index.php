<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Calendario;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntHorariosAreasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Disponiblidad de repartidores';

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
  '@web/webAssets/js/horarios-areas/index.js',
  ['depends' => [\app\assets\AppAsset::className()]]
);
?>
<div class="ent-horarios-areas-index">
    <div class="row">
        <div class="col-md-12">
              <!-- Example Tabs Solid -->
              <div class="example-wrap">
                <div class="nav-tabs-horizontal" data-plugin="tabs">
                  <ul class="nav nav-tabs nav-tabs-solid" role="tablist">
                    <?php
                    $active = true;
                    foreach($areas as $area){
                    ?>  
                    <li class="nav-item" role="presentation">
                      <a class="nav-link <?=$active?"active":""?>" data-toggle="tab" href="#panel_<?=$area->id_area?>" aria-controls="panel_<?=$area->id_area?>" role="tab" 
                        aria-expanded="true"><?=$area->txt_nombre?>
                      </a>
                    </li>
                    
                    <?php
                    $active = false;
                    }
                    ?>
                  </ul>
                  <div class="tab-content">
                    <?php
                    $active = true;
                    foreach($areas as $area){
                    ?>  
                        <div class="tab-pane <?=$active?"active":""?>" id="panel_<?=$area->id_area?>" role="tabpanel" aria-expanded="true">
                            <div class="table-responsive">
                              <table class="table table-hover">
                                <thead>
                                  <tr>
                                    <th>Día</th>
                                    <th>Horario</th>
                                    <th class="text-center">Disponibilidad</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  foreach($area->entHorariosAreas as $disponibilidad){
                                    ?>
                                    <tr>
                                      <td><?=Calendario::nombreDia($disponibilidad->id_dia)?></td>
                                      <td><?=$disponibilidad->txt_hora_inicial?> - <?=$disponibilidad->txt_hora_final?></td>
                                      <td>
                                        <div class="row">
                                          <div class="col-md-6 offset-md-3">
                                            <div class="form-group">
                                              <input class="js-cupo form-control" type="text" data-token="<?=$disponibilidad->id_horario_area?>" value="<?=$disponibilidad->num_disponibles?>"/>
                                            </div>
                                          </div>
                                        </div>
                                          
                                          
                                      </td>
                                      
                                    </tr>
                                    <?php
                                  }
                                  ?>
                                </tbody>
                              </table>
                            </div>
                        </div>
                    <?php
                    $active = false;
                    }
                    ?>
                  </div>
                </div>
              </div>
              <!-- End Example Tabs Solid -->
            </div>
    </div>
   
</div>
