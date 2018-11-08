<?php

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\web\View;


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
