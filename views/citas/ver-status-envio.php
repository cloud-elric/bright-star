<?php
// use yii\helpers\Url;
use app\models\Calendario;
use app\assets\AppAsset;
use yii\helpers\Html;
use app\models\EntEvidenciasCitas;
use yii\helpers\Url;

use app\models\Constantes;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\models\Files;
$this->title = $envio->txt_tracking;
$this->params['classBody'] = "site-navbar-small site-menubar-hide";

$this->params['breadcrumbs'][] = [
    'label' => '<i class="icon pe-headphones"></i> Citas', 
    'url' => ['index'],
    'template'=>'<li class="breadcrumb-item">{link}</li>', 
    'encode' => false
];

$this->registerJsFile(
    '@web/webAssets/templates/classic/global/vendor/magnific-popup/jquery.magnific-popup.min.js',
    ['depends' => [\app\assets\AppAsset::className()]]
  );

  $this->registerJsFile(
    '@web/webAssets/js/citas/ver-status-envio.js',
    ['depends' => [\app\assets\AppAsset::className()]]
  );

  $this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/magnific-popup/magnific-popup.min.css',
    ['depends' => [\app\assets\AppAsset::className()]]
  );

  $this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/dropify/dropify.css',
    ['depends' => [AppAsset::className()]]
  ); 

  $this->registerJsFile(
    '@web/webAssets/templates/classic/global/vendor/dropify/dropify.min.js',
    ['depends' => [AppAsset::className()]]
);

$this->registerJsFile(
    '@web/webAssets/templates/classic/global/js/Plugin/dropify.js',
    ['depends' => [AppAsset::className()]]
);


$cita = $envio->idCita;
$hasEvidencia = EntEvidenciasCitas::find()->where(["id_cita"=>$cita->id_cita])->one();
$usuarioLogueado = EntUsuarios::getUsuarioLogueado();

 $this->params['breadcrumbs'][] = [
     'label' => '<i class="icon wb-eye"></i> '.$cita->txt_identificador_cliente,
     'url'=>['view', 'token'=>$cita->txt_token],
     'template'=>'<li class="breadcrumb-item">{link}</li>', 
     'encode' => false];
?>

<div class="citas-status-send">

    <div class="row">
        <div class="col-md-8">

                <div class="citas-stat-send-grals">
                <h3>
                    <span>Datos Generales</span>
                    <?php 
                    if($respuestaApi->TrackingLink && $usuarioLogueado->txt_auth_item==Constantes::USUARIO_MASTER_BRIGHT_STAR){
                    ?>

                    <a class="badge" href="<?=$respuestaApi->TrackingLink?>" target="_blank">Monitorear envío</a>

                    <?php
                    }
                    ?>
                    
                </h3>
                <div class="row">
                    <div class="col-md-4 citas-stat-send-item">
                        <h5>Fecha</h5>
                        <p><?=Calendario::getDateComplete($respuestaApi->Fecha)?></p>
                    </div>
                    <div class="col-md-4 citas-stat-send-item">
                        <h5>Evento</h5>
                        <p><?=$respuestaApi->Evento?></p>
                    </div>
                    <div class="col-md-4 citas-stat-send-item">
                        
                        <?php
                        if($respuestaApi->ClaveMotivo>0){
                            
                            echo "<p><h5>Motivo de cancelación</h5>".$respuestaApi->Motivo."</p>";
                        }
                        ?>
                        
                    </div>
                </div>

            </div>


            <div class="citas-stat-send-record">

                <div class="citas-stat-send-record-head">
                    <h3>Historial</h3>
                    <div class="panel-actions panel-actions-keep">
                        <!-- <a class="badge" href="<?=$respuestaApi->TrackingLink?>" target="_blank">Link</a> -->
                    </div>
                </div>

                <div class="citas-stat-send-record-panel">
                    
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Evento</th>
                                <th>Fecha & Hora</th>
                                <th>Motivo</th>
                                <th>Evidencia</th>
                                <th>Firma</th>
                                <th>Comentarios</th>
                                <th>Mapa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($historico->History)){
                                    usort($historico->History, function($a, $b) { return strtotime($b->Fecha) - strtotime($a->Fecha); });
                                    $localizaciones = [];
                                    $index = count($historico->Images)-1;
                                    foreach($historico->History as $key=>$historial):
                                        $localizaciones[] = (isset($historial->Geolocation) && $historial->Geolocation)?$historial->Geolocation:null;
                            ?>
                                <tr>
                                    <td>
                                        <div class="badge">
                                            <?=$historial->Evento?>
                                        </div>
                                    </td>
                                    <td>
                                        <span>
                                            <i class="wb wb-time"></i>
                                            <?=Calendario::getDateCompleteHour($historial->Fecha)?>  
                                        </span>
                                    </td>
                                    <td>
                                        <?=$historial->Motivo?>
                                    </td>
                                    <td>
                                        <?php
                                        if(isset($historico->Images) && $index>=0){
                                            if(isset($historico->Images[$index])){

                                             ?>
                                             <a class="magnific" href="<?=$historico->Images[$index]->Link?>" >
                                                <img class="avatar avatar-sm" src="<?=$historico->Images[$index]->Link?>"  data-container="body" title="">
                                            </a>
                                             <?php
                                            }
                                        }    
                                        ?>

                                        <!-- <img class="avatar avatar-sm" src="http://via.placeholder.com/200x200" data-toggle="tooltip" data-original-title="Crystal Bates" data-container="body" title=""> -->
                                    </td>
                                    <td>
                                        <!-- <img class="avatar avatar-sm" src="http://via.placeholder.com/200x200" data-toggle="tooltip" data-original-title="Crystal Bates" data-container="body" title=""> -->
                                        <?php
                                        if($historial->Firma){
                                            echo '<a class="magnific" href="'.$historial->Firma.'"><img class="avatar avatar-sm" src="'.$historial->Firma.'"></a>';
                                        }
                                        ?>
                                        
                                    </td>
                                    <td>
                                        <?=$historial->Comentario?>
                                    </td>
                                    <td>
                                        <?php 
                                            if(isset($localizaciones[$key])){
                                                
                                                $coordenadas = explode("|" , $localizaciones[$key]);

                                                $latitud = $coordenadas[0];
                                                $longitud = $coordenadas[1];
                                                ?>
                                                
                                                    <a class="magnific" href="https://maps.googleapis.com/maps/api/staticmap?center=<?=$latitud?>,<?=$longitud?>&markers=color:red%7C<?=$latitud?>,<?=$longitud?>&zoom=19&size=600x400&key=AIzaSyBlkuXFs8ehiHk8mS_nozNbUoQH1_PyaLg" >
                                                        <img  class="avatar avatar-sm" src="https://maps.googleapis.com/maps/api/staticmap?center=<?=$latitud?>,<?=$longitud?>&markers=color:red%7C<?=$latitud?>,<?=$longitud?>&zoom=19&size=600x400&key=AIzaSyBlkuXFs8ehiHk8mS_nozNbUoQH1_PyaLg" alt="...">
                                                    </a>
                                                   
                                                    
                                            <?php
                                            }else{
                                                echo "Sin posición";
                                            }
                                            
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            $index--;
                                endforeach;
                                }
                            ?>
                        </tbody>
                    </table>

                </div>
                
            </div>

        </div>
        <div class="col-md-4">

            <div class="citas-stat-send-media">

                <div class="citas-stat-send-item">
                    <h5>Mapa</h5>
                    <?php 
                        if($respuestaApi->Position){
                            
                            $coordenadas = explode("|" , $respuestaApi->Position);

                            $latitud = $coordenadas[0];
                            $longitud = $coordenadas[1];
                            ?>
                            <div class="card card-shadow">
                                <figure class="card-img-top overlay-hover overlay">
                                <img class="overlay-figure overlay-scale" src="https://maps.googleapis.com/maps/api/staticmap?center=<?=$latitud?>,<?=$longitud?>&markers=color:red%7C<?=$latitud?>,<?=$longitud?>&zoom=19&size=600x400&key=AIzaSyBlkuXFs8ehiHk8mS_nozNbUoQH1_PyaLg" alt="...">
                                <p></p>
                                <p>Latitud: <?=$latitud?></p>
                                <p>Longitud: <?=$longitud?></p>
                                </figure>
                            </div>
                        <?php
                        }else{
                            echo "Sin posición";
                        }
                        
                    ?>
                    
                </div>

                <!-- <div class="citas-stat-send-item">
                    <h5>Firma</h5>
                    
                    <?php
                    if($respuestaApi->Image){
                    ?>
                    <div class="card card-shadow">
                        <figure class="card-img-top overlay-hover overlay">
                            <img class="overlay-figure overlay-scale" src="<?=$respuestaApi->Image?>" alt="...">
                            
                        </figure>
                    </div>
                    <?php
                    }else{
                        echo "<p>Sin firma</p>";
                    }
                    ?>        
                </div>

                <div class="citas-stat-send-item">
                    <h5>Evidencias</h5>
                    <?php
                    if(isset($respuestaApi->ImagesLinks)){
                        foreach($respuestaApi->ImagesLinks as $images){
                            ?>
                            <div class="card card-shadow">
                                <figure class="card-img-top overlay-hover overlay">
                                    <img class="overlay-figure overlay-scale" src="<?=$images->Link?>" alt="...">
                                 
                                </figure>
                            </div>
                            <?php
                        }
                    }    
                    ?>
                </div> -->

            </div>

            <div class="citas-stat-send-evidencia">
                    
                <h5>
                    Subir evidencia
                    <?php
                    $ubicacionArchivo = $cita->pathBaseEvidencia.$cita->txt_telefono.".pdf";
                    
                    $token = false;
                    if(Files::existeArchivo($ubicacionArchivo)){
                        $token = $cita->txt_identificador_cliente;
                    }else if($hasEvidencia){
                        $token = $hasEvidencia->txt_token;
                    };
                    ?>
                    <a class="badge float-right js-descargar-evidencia" style="display:<?=$token?'block':'none'?>" href="<?=$token?Url::base()."/citas/descargar-evidencia?token=".$token:''?>" target="_blank"><i class="icon wb-download" aria-hidden="true"></i></a>
                </h5>

                <div class="card card-dropify">
                    
                    <?= Html::beginForm(['citas/upload-file'], 'post', ['enctype' => 'multipart/form-data','id' => "form-upload-file"]) ?>
                    <?= Html::fileInput("file-upload", "", [
                            "id"=>"input-image-upload", 
                            "data-plugin"=>"dropify", 
                            "data-max-file-size"=>"50M", 
                            "data-allowed-file-extensions"=>"pdf",
                            
                        ])?>
                        
                    <div class="card-block">
                    <h4 class="card-title">
                        <?=Html::submitButton("<span class='ladda-label'>Guardar evidencia</span>", ["class"=>"btn btn-success btn-block ladda-button", "id"=>"btn-upload-file", "data-style"=>"zoom-in"])?>
                    </h4>
                    </div>
                    <?=Html::endForm()?>
                </div>

            </div>
            
        </div>

    </div>

</div>

<input id="token-cita" value="<?=$cita->txt_token?>" type="hidden"/>