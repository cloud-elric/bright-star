<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntCodigoPostalDisponibilidad */

$this->title = 'Crear disponibilidad';
// $this->params['breadcrumbs'][] = ['label' => 'Codigo postal disponibilidad', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-codigo-postal-disponibilidad-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
