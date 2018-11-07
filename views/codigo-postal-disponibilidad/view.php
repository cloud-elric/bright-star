<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EntCodigoPostalDisponibilidad */

$this->title = $model->txt_codigo_postal;
?>
<div class="ent-codigo-postal-disponibilidad-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id_disponiblidad], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_disponiblidad], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id_disponiblidad',
            'txt_codigo_postal',
            'num_dia',
            'txt_hora_inicial',
            'txt_hora_final',
            // 'b_habilitado',
        ],
    ]) ?>

</div>
