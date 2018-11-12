<?php

namespace app\controllers;

use Yii;
use app\models\EntHorariosAreas;
use app\models\EntHorariosAreasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;
use yii\helpers\Json;
use app\models\EntCitas;
use app\models\Calendario;
use app\modules\ModUsuarios\models\Utils;
use app\models\ResponseServices;
use app\models\CatCats;
use app\models\CatAreas;
use yii\web\HttpException;
use app\models\EntCodigoPostalDisponibilidad;
use yii\helpers\ArrayHelper;

/**
 * HorariosAreasController implements the CRUD actions for EntHorariosAreas model.
 */
class HorariosAreasController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all EntHorariosAreas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $areas = CatAreas::find()->where(["b_habilitado"=>1])->all();

        return $this->render('index', [
            "areas"=>$areas
        ]);
    }

    /**
     * Displays a single EntHorariosAreas model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EntHorariosAreas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EntHorariosAreas();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_horario_area]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EntHorariosAreas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_horario_area]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing EntHorariosAreas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the EntHorariosAreas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return EntHorariosAreas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntHorariosAreas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetHorariosDisponibilidadByCac($cac = null){
        $respuesta = new ResponseServices();
        $out = [];

        if (isset($_POST['depdrop_all_params']['entcitas-id_cat'])&&
        isset($_POST['depdrop_all_params']['entcitas-fch_cita'])) {

            $id = $_POST['depdrop_all_params']['entcitas-id_cat'];
            $fecha = $_POST['depdrop_all_params']['entcitas-fch_cita'];
           

            if(!$id){
                echo Json::encode(['output' => $out, 'selected'=>'']);
                return;
            }
           
            if(!$fecha){
                echo Json::encode(['output' => $out, 'selected'=>'']);
                return;
            }
            // fch_cita id_tipo_entrega
            $numDia = Calendario::getNumberDayWeek($fecha);
            if($numDia==1){
                $cac = CatCats::find()->where(["id_cat"=>$id, "b_lunes"=>1])->one();
               
            }

            if($numDia==2){
                $cac = CatCats::find()->where(["id_cat"=>$id, "b_martes"=>1])->one();
               
            }

            if($numDia==3){
                $cac = CatCats::find()->where(["id_cat"=>$id, "b_miercoles"=>1])->one();
               
            }

            if($numDia==4){
                $cac = CatCats::find()->where(["id_cat"=>$id, "b_jueves"=>1])->one();
               
            }

            if($numDia==5){
                $cac = CatCats::find()->where(["id_cat"=>$id, "b_viernes"=>1])->one();
                
            }

            if($numDia==6){
                $cac = CatCats::find()->where(["id_cat"=>$id, "b_sabado"=>1])->one();
               
            }

            if($numDia==7){
                $cac = CatCats::find()->where(["id_cat"=>$id, "b_domingo"=>1])->one();
               
            }

            

            $selected  = null;
            if ($id != null && $cac) {
                $selected = '';
                
                            $out[0] = [
                                'id' => $cac->txt_horario1, 
                                'name' => $cac->txt_horario1,
                                'cantidad'=>0];

                            $out[1] = [
                                'id' => $cac->txt_horario2,
                                'name' => $cac->txt_horario2,
                                    'cantidad'=>0];   
                        
                      

                        $selected = $cac;

                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        
        echo Json::encode(['output' => $out, 'selected'=>'']);
        

    }

    public function actionGetHorariosDisponibilidadByArea($horario = null){
        $respuesta = new ResponseServices();
        $out = [];
        $validarCupo = true;

        if (isset($_POST['depdrop_all_params']['entcitas-id_area']) &&
            isset($_POST['depdrop_all_params']['entcitas-fch_cita'])) {

            $id = $_POST['depdrop_all_params']['entcitas-id_area'];
            $fecha = $_POST['depdrop_all_params']['entcitas-fch_cita'];
            $tipoEntrega = 1;//Default standard
            $fechaCreacion = $_POST['depdrop_all_params']['entcitas-fch_creacion'];
            $cp = $_POST['depdrop_all_params']['entcitas-txt_codigo_postal'];

            if(!$fecha){
                echo Json::encode(['output' => $out, 'selected'=>'']);
                return;
            }
            // fch_cita id_tipo_entrega
            $numDia = Calendario::getNumberDayWeek($fecha);
            $fechaFormateada = Utils::changeFormatDateInput($fecha);

            if($tipoEntrega==2){
                $list = EntHorariosAreas::find()->andWhere(['id_area'=>$id, 'id_dia'=>7])->asArray()->all();
            }else if($tipoEntrega==1){
                $hd = EntCodigoPostalDisponibilidad::find()
                ->select("id_horario")
                ->where([
                    "id_area"=>$id, 
                    "b_habilitado"=>0,
                    "txt_codigo_postal"=>$cp
                ])->all();
                $horariosDeshabilitados = [];
                foreach($hd as $h){
                    $horariosDeshabilitados[]=$h->id_horario;
                }

                $list = EntHorariosAreas::find()
                ->andWhere(['id_area'=>$id, 'id_dia'=>$numDia])
                 ->andWhere(["not in", "id_horario_area", $horariosDeshabilitados])
                ->asArray()->all();
            }

            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $disponibilidad) {

                    $horariosOcupados = EntCitas::find() 
                            ->where(new Expression('date_format(fch_cita, "%Y-%m-%d") = date_format("'.$fechaFormateada.'", "%Y-%m-%d")') )
                            ->andWhere(['id_horario'=>$disponibilidad["id_horario_area"]])->count();

                    if($tipoEntrega==2){        
                        $out[] = [
                            'id' => $disponibilidad['id_horario_area'], 
                            'name' => $disponibilidad['txt_hora_inicial']." - ".$disponibilidad['txt_hora_final'],
                            'cantidad'=>''];
                        
                    }else{
                        if((!$fechaCreacion) && $validarCupo){
                            if(($disponibilidad['num_disponibles']-$horariosOcupados)>0){
                                $out[] = [
                                    'id' => $disponibilidad['id_horario_area'], 
                                    'name' => $disponibilidad['txt_hora_inicial']." - ".$disponibilidad['txt_hora_final'],
                                    'cantidad'=>$disponibilidad['num_disponibles']-$horariosOcupados];
                            }
                        }else{
                            $out[] = [
                                'id' => $disponibilidad['id_horario_area'], 
                                'name' => $disponibilidad['txt_hora_inicial']." - ".$disponibilidad['txt_hora_final'],
                                'cantidad'=>$disponibilidad['num_disponibles']-$horariosOcupados];
                        }

                        


                    }    

                    if ($i == 0) {
                        $selected = $horario;
                    }

                    
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        
        echo Json::encode(['output' => $out, 'selected'=>'']);
        

    }

    public function actionUpdateCupo($token=null){
        $disponibilidad = EntHorariosAreas::find()->where(["id_horario_area"=>$token])->one();

        if(!$disponibilidad){
            throw new HttpException(404, "No se encontro el registro en la base de datos");
        }

        if(isset($_POST["cupo"]) && $_POST["cupo"]){
            $disponibilidad->num_disponibles = $_POST["cupo"];
            if(!$disponibilidad->save()){
                throw new HttpException(500, "No se pudo guardar el registro");
            }
        }else{
            throw new HttpException(500, "No se enviaron los datos de la manera correcta. Falto el parametro CUPO y debe de ser enviado por POST");
        }
    }
}
