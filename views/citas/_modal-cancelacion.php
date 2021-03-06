
<?php 
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use app\models\Constantes;
use yii\bootstrap\Html;
use app\models\CatTiposCancelacion;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

    $model->scenario = 'cancelar';
    Modal::begin([
        'options'=>[
            'tabindex' => false
        ],
        'header'=>'<h4>Motivo de cancelación</h4>',
        'id'=>'cita-cancelacion-modal',
        
        //'size'=>'modal-lg',
    ]);

        $form = ActiveForm::begin([
            'id'=>'cita-cancelacion-form',
            'action'=>'cancelar?token='.$model->txt_token
            ]);

            echo $form->field($model, 'txt_motivo_cancelacion_rechazo')->textarea();



        echo Html::submitButton('Cancelar cita', ['class' => 'btn btn-warning']);

    ActiveForm::end();
    Modal::end();

?>