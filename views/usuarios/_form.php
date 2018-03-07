<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

?>
<div class="row">
    <div class="col-md-4">
        <div class="user-file">
            <a class="user-file-a js-img-avatar">
                <img class="js-image-preview" src="<?= Url::base() . "/webAssets/images/site/user.png" ?>">
            </a>
        </div>
    </div>
    <div class="col-md-8">

        <?php $form = ActiveForm::begin([
            'id' => 'form-guardar-usuario',
                    //'options' => ['class' => 'form-horizontal'],
                    //'enableAjaxValidation' => true,
            'enableClientValidation' => true,
        ]); ?>

            <?= $form->field($model, 'image')->fileInput(["class" => "hidden-xxl-down"])->label(false) ?> 
            <div class="row">
                <div class="col-md-6">

                    <h4>Datos Generales</h4>

                    <?= $form->field($model, 'txt_auth_item')
                        ->widget(Select2::classname(), [
                            'data' => ArrayHelper::map($roles, 'name', 'description'),
                            'language' => 'es',
                            'options' => ['placeholder' => 'Seleccionar tipo de usuario'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(false);
                    ?> 

                    <?= $form->field($model, 'txt_username')->textInput(['maxlength' => true, 'placeholder' => 'Nombre'])->label(false) ?>

                    <?= $form->field($model, 'txt_apellido_paterno')->textInput(['maxlength' => true, 'placeholder' => 'Apellido paterno'])->label(false) ?>

                    <?= $form->field($model, 'txt_email')->textInput(['maxlength' => true, 'placeholder' => 'Email'])->label(false) ?>

                
                </div>

                <div class="col-md-6">

                    <h4>Datos de Usuario</h4>

                    <div class="form-group">
                        <input type="text" class="form-control form-input-usuario" disabled placeholder="usuario">
                    </div>

                    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'placeholder' => 'Contraseña'])->label(false) ?>

                    <?= $form->field($model, 'repeatPassword')->passwordInput(['maxlength' => true, 'placeholder' => 'Repetir contraseña'])->label(false)->hint('<span class="form-pass-info"><i class="icon wb-help" aria-hidden="true"></i></span>') ?>

                    <div class="form-group">
                        <?= Html::submitButton('<span class="ladda-label"><i class="icon wb-plus"></i> Guardar usuario</span>', ['class' => "btn btn-success ladda-button btn-usuarios-add", "data-style" => "zoom-in", "id" => "btn-guardar-usuario"]) ?>
                    </div>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>