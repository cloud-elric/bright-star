<?php

use yii\helpers\Html;
use yii\web\View;
use app\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model app\models\EntCodigoPostalDisponibilidad */

$this->registerJsFile(
    '@web/webAssets/templates/classic/global/vendor/bootstrap-datepicker/bootstrap-datepicker.js',
    ['depends' => [AppAsset::className()]]
);
$this->registerJsFile(
    'https://rawgit.com/jonthornton/jquery-timepicker/master/jquery.timepicker.min.js',
    ['depends' => [AppAsset::className()]]
);
$this->registerCssFile(
    'https://rawgit.com/jonthornton/jquery-timepicker/master/jquery.timepicker.css',
    ['depends' => [AppAsset::className()]]
);
$this->registerJsFile(
    'https://rawgit.com/jonthornton/Datepair.js/master/dist/datepair.js',
    ['depends' => [AppAsset::className()]]
);
$this->registerJsFile(
    'https://rawgit.com/jonthornton/Datepair.js/master/dist/jquery.datepair.js',
    ['depends' => [AppAsset::className()]]
);

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
    '@web/webAssets/js/disponibilidad/create.js',
    ['depends' => [AppAsset::className()]]
);

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
<?php
$this->registerJs('
    $(document).ready(function() {
        console.log("dsssdsdssdsd");
        $(".time").timepicker({
            "timeFormat": "H:i",
            "minTime": "6:00",
            "maxTime": "23:00",
        });
    });
',
View::POS_END
);
?>
