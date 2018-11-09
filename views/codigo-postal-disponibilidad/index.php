<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\modules\ModUsuarios\models\EntUsuarios;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntCodigoPostalDisponibilidadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerJsFile(
    '@web/webAssets/js/disponibilidad/index.js',
    ['depends' => [\app\assets\AppAsset::className()]]
);

$this->title = 'Codigo Postal Disponibilidad';
?>
<div class="ent-codigo-postal-disponibilidad-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear disponibilidad', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id_disponiblidad',
            [
                'attribute' => 'txt_codigo_postal',
                'format'=>'raw',
                'value'=>function($data){
                    return Html::a(
                        Html::encode($data->txt_codigo_postal),
                        Url::to(['codigo-postal-disponibilidad/view', 'id' => $data->id_disponiblidad]), 
                        [
                            'class'=>"codigo-postal"
                        ]
                    );
                }
            ],
            [
                'attribute' => 'num_dia',
                'format' => 'raw',
                'value' => function($data){
                    return $data->getDiasSemana();
                }
            ],
            'txt_hora_inicial',
            'txt_hora_final',
            // 'b_habilitado',
            [
                'attribute' => 'b_habilitado',
                'filter'=>[EntUsuarios::STATUS_ACTIVED=>'Activo', EntUsuarios::STATUS_BLOCKED=>'Inactivo'],
                'format'=>'raw',
                
                'value'=>function($data){
    
                $activo = $data->b_habilitado == 1?'active':'';
                $inactivo = $data->b_habilitado == 0?'active':'';
                    
                return '<div class="btn-group" data-toggle="buttons-checkbox" role="group">
                    <label class="btn btn-active '.$activo.'"  data-token="'.$data->id_disponiblidad.'">
                        <input class="js-activar-cp" type="radio" name="options" autocomplete="off" value="male" checked />
                        Activo
                    </label>
                    <label class="btn btn-inactive '.$inactivo.'" data-token="'.$data->id_disponiblidad.'">
                        <input class="js-bloquear-cp"  type="radio" name="options" autocomplete="off" value="female" />
                        Inactivo
                    </label>
                </div>';
                }
              ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
