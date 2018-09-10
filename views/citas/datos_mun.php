<?php
use yii\helpers\Url;
use app\assets\AppAsset;

$this->registerJsFile(
    '@web/webAssets/js/citas/importar.js',
    ['depends' => [AppAsset::className()]]
);
?>
<h2>Cargar archivo excel</h2>
<div class="row">
    <div class="col-md-4">
        <form action="#">
            <input class="js-input-file" name="file-import" type="file" data-url="<?= Url::base() ?>">
        </form>
    </div>
    <div class="col-md-8">

    </div>
        
    <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
        </div>
    </div>
</div>
