<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model app\models\EntCodigoPostalDisponibilidad */

$this->registerJsFile(
    '@web/webAssets/js/disponibilidad/view.js',
    ['depends' => [AppAsset::className()]]
);

$this->title = $model->txt_codigo_postal;
?>
<div class="ent-codigo-postal-disponibilidad-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id_disponiblidad], ['class' => 'btn btn-primary']) ?>
        <?= Html::button('Eliminar', [
            'class' => 'btn btn-danger js-eliminar-disponibilidad',
            'data-id' => $model->id_disponiblidad,
            'data-url' => Url::base()
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id_disponiblidad',
            'txt_codigo_postal',
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
        ],
    ]) ?>

</div>
