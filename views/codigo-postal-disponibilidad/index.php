<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntCodigoPostalDisponibilidadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Codigo Postal Disponibilidad';
$this->params['breadcrumbs'][] = $this->title;
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
            'num_dia',
            'txt_hora_inicial',
            'txt_hora_final',
            // 'b_habilitado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
