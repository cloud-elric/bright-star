<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EntCodigoPostalDisponibilidad */

$this->title = $model->id_disponiblidad;
$this->params['breadcrumbs'][] = ['label' => 'Ent Codigo Postal Disponibilidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-codigo-postal-disponibilidad-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_disponiblidad], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_disponiblidad], [
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
            'id_disponiblidad',
            'id_municipio',
            'id_area',
            'id_horario',
            'txt_codigo_postal',
            'b_habilitado',
        ],
    ]) ?>

</div>
