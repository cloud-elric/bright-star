<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use app\components\CustomLinkSorter;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Calendario;
use kartik\export\ExportMenu;
use app\models\EntCitas;
use app\models\Constantes;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\models\CatTiposTramites;
use app\models\EntUsuarios;
use app\models\Permisos;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ModUsuarios\models\EntUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

 $this->title = 'Citas';
 $this->params['classBody'] = "site-navbar-small site-menubar-hide";
 if(\Yii::$app->user->can(Constantes::USUARIO_CALL_CENTER)){
      $this->params['headerActions'] = '<a class="btn btn-success ladda-button no-pjax" href="'.Url::base().'/citas/create" data-style="zoom-in"><span class="ladda-label no-pjax"><i class="icon wb-plus"></i>Agregar</span></a>';
 }
$this->params['breadcrumbs'][] = [
    'label' => '<i class="icon pe-7s-headphones"></i>Citas',
    'template'=>'<li class="breadcrumb-item">{link}</li>', 
    'encode' => false];

// $this->registerCssFile(
//     '@web/webAssets/css/citas/index.css',
//     ['depends' => [\app\assets\AppAsset::className()]]
// );

$this->registerJsFile(
    '@web/webAssets/js/citas/index.js',
    ['depends' => [\app\assets\AppAsset::className()]]
);

?>  
<div class="panel-table">
        <?php
        require(__DIR__ . '/../components/select2.php');
        echo GridView::widget([
                // 'tableOptions' => [
                //     "class" => "table"
                // ],
                'filterModel' => $searchModel,
                'pjax'=>true,
                'pjaxSettings'=>[
                    'options'=>[
                      'linkSelector'=>"a:not(.no-pjax)",
                      'id'=>'pjax-citas'
                    ]
                  ],
                'dataProvider' => $dataProvider,
                'columns' =>[
                    'txt_identificador_cliente',
                    [
                        'filter'=>ArrayHelper::map($status, 'id_statu_cita', 'txt_nombre'),
                        'filterInputOptions'=>[
                            'class'=>'form-control',
                            'prompt'=>"Ver todos"
                        ],
                        'attribute' => 'id_status',
                        'format'=>'raw',
                        'value'=>function($data){
                            
                            $statusColor = EntCitas::getColorStatus($data->id_status);
                            $classActualizar = "actualizar-envio";
                            $isBuscar = false;
                            $dataEnvio = '';
                            if($data->id_envio && (Constantes::STATUS_CANCELADO)){
                                $isBuscar = true;
                                $dataEnvio = $data->txtTracking;
                            }
                            return Html::a(
                                Html::encode($data->idStatus->txt_nombre),
                                Url::to(['citas/view', 'token' => $data->txt_token]), 
                                [
                                    'id'=>"js-cita-envio-".$data->txt_token,
                                    'data-envio'=>$dataEnvio,
                                    'class'=>'btn badge '.$statusColor.' no-pjax '.($isBuscar?$classActualizar:''),
                                ]
                            );
                        }
                    ],
                    'txt_telefono',
                    [
                        'attribute'=>'nombreCompleto',
                        //'filter'=>"",
                        'format'=>'raw',
                        'value'=>function($data){
                            return Html::encode($data->nombreCompleto);
                        }
                    ],
                    [
                        'filter'=>ArrayHelper::map(CatTiposTramites::find()->all(), 'id_tramite', 'txt_nombre'),
                        'filterInputOptions'=>[
                            'class'=>'form-control',
                            'prompt'=>"Ver todos"
                        ],
                        'attribute'=>'id_tipo_tramite',
                        'value'=>'idTipoTramite.txt_nombre'
                    ],
                    [
                        'filter'=>DatePicker::widget([
                            'model'=>$searchModel,
                            'attribute'=>'fch_creacion',
                            'pickerButton'=>false,
                            'removeButton'=>false,
                            'type' => DatePicker::TYPE_INPUT,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd-mm-yyyy',
                                'clearBtn'=>true,
                            ]
                        ]),
                        'attribute'=>'fch_creacion',
                        'format'=>'raw',
                        'value'=>function($data){
            
                            return Calendario::getDateSimple($data->fch_creacion);
                        }
                    ],
                    [
                        'filter'=>DatePicker::widget([
                            'model'=>$searchModel,
                            'attribute'=>'fch_cita',
                            'pickerButton'=>false,
                            'removeButton'=>false,
                            'type' => DatePicker::TYPE_INPUT,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd-mm-yyyy'
                            ]
                        ]),
                        'attribute'=>'fch_cita',
                        'format'=>'raw',
                        'value'=>function($data){
                            if(!$data->fch_cita){
                                return "(no definido)";
                            }
                            return Calendario::getDateSimple($data->fch_cita);
                        }
                    ],
                    [
                        'attribute'=>'txtTracking',
                        'value'=>'txtTracking',
                        'format'=>'raw',
                        'filterInputOptions' => [
                            'autocomplete' => 'nofill', 
                            'class'=>"form-control"
                          ],
                        'value'=>function($data){

                            if($data->id_envio){
                                if(Permisos::canUsuarioVerStatusEnvio()){
                                    return Html::a(
                                        Html::encode($data->txtTracking),
                                        Url::to(['citas/ver-status-envio', 'token' => $data->idEnvio->txt_token]),
                                        [
                                            'class'=>'id-send no-pjax'
                                        ]
                                    );
                                }

                                return "<span class='id-send-error'>".Html::encode($data->txtTracking)."</span>";
                            }

                            return "<span class='id-send-error'>---</span>";

                            
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
                // 'toolbar' => [
                //     '{export}',
                // ],
                // 'export' => [
                //     "pjaxContainerId"=>'pjax-citas',
                //     'label' => 'Exportar',
                //     'fontAwesome' => true,
                //     'showConfirmAlert'=>false,
                //     'enableFormatter'=>false,
                //     'itemsAfter'=> [
                //         '<li role="presentation" class="divider"></li>',
                //         '<li class="dropdown-header">Export todos los datos</li>',
                //         $fullExportMenu
                //     ],
                //     'options'=>[
                //         'class' => 'btn btn-exportar',
                //     ]
                // ],
                // 'exportConfig'=>[
                //     GridView::CSV => [
                //         'label' => Yii::t('kvgrid', 'CSV'),
                //         'icon' =>'file-code-o', 
                //         'iconOptions' => false,
                //         'showHeader' => true,
                //         'showPageSummary' => true,
                //         'showFooter' => true,
                //         'showCaption' => true,
                //         'filename' => Yii::t('kvgrid', 'grid-export'),
                //         'alertMsg' => Yii::t('kvgrid', 'The CSV export file will be generated for download.'),
                //         'options' => ['title' => Yii::t('kvgrid', 'Comma Separated Values')],
                //         'mime' => 'application/csv',
                //         'writer' => ExportMenu::FORMAT_CSV,
                //         'config' => [
                //             'colDelimiter' => ",",
                //             'rowDelimiter' => "\r\n",
                //         ],
                //     ],
                // ],
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


<style>
.datepicker{
    z-index:9999 !important;
}

</style>
