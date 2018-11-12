<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntCodigoPostalDisponibilidad */

$this->title = 'Update Ent Codigo Postal Disponibilidad: ' . $model->id_disponiblidad;
$this->params['breadcrumbs'][] = ['label' => 'Ent Codigo Postal Disponibilidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_disponiblidad, 'url' => ['view', 'id' => $model->id_disponiblidad]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ent-codigo-postal-disponibilidad-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
