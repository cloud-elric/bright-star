<?php

namespace app\controllers;

use Yii;
use app\models\EntCodigoPostalDisponibilidad;
use app\models\EntCodigoPostalDisponibilidadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AccessControlExtend;
use app\models\ResponseServices;

/**
 * CodigoPostalDisponibilidadController implements the CRUD actions for EntCodigoPostalDisponibilidad model.
 */
class CodigoPostalDisponibilidadController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControlExtend::className(),
                'only' => ['index', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['master-bright-star'],
                    ],
                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'logout' => ['post'],
            //     ],
            // ],
       ];
    }

    /**
     * Lists all EntCodigoPostalDisponibilidad models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EntCodigoPostalDisponibilidadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EntCodigoPostalDisponibilidad model.
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
     * Creates a new EntCodigoPostalDisponibilidad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EntCodigoPostalDisponibilidad();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_disponiblidad]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EntCodigoPostalDisponibilidad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_disponiblidad]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing EntCodigoPostalDisponibilidad model.
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
     * Finds the EntCodigoPostalDisponibilidad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return EntCodigoPostalDisponibilidad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntCodigoPostalDisponibilidad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBloquearCp($id = null){
        $response = new ResponseServices();

        if($id){
            $cp = $this->findModel($id);
            $cp->b_habilitado = 0;

            if($cp->save()){
                $response->status = "success";
                $response->message = "Se bloqueo correctamente";
                
                return $response;
            }
        }
        return $response;
    }

    public function actionActivarCp($id = null){
        $response = new ResponseServices();

        if($id){
            $cp = $this->findModel($id);
            $cp->b_habilitado = 1;

            if($cp->save()){
                $response->status = "success";
                $response->message = "Se activo correctamente";

                return $response;
            }
        }
        return $response;
    }

    public function actionCrearDisponibilidad(){

        if(isset($_POST['EntCodigoPostalDisponibilidad']['txt_codigo_postal']) && isset($_POST['EntCodigoPostalDisponibilidad']['txt_hora_inicial']) && isset($_POST['EntCodigoPostalDisponibilidad']['txt_hora_final']) && isset($_POST['dias'])){

            $dias = explode(',', $_POST['dias']);
            $transaction = Yii::$app->db->beginTransaction();
            foreach($dias as $dia){
                try {
                    $model = new EntCodigoPostalDisponibilidad();
                    $model->txt_codigo_postal = $_POST['EntCodigoPostalDisponibilidad']['txt_codigo_postal'];
                    $model->txt_hora_inicial = $_POST['EntCodigoPostalDisponibilidad']['txt_hora_inicial'];
                    $model->txt_hora_final = $_POST['EntCodigoPostalDisponibilidad']['txt_hora_final'];
                    $model->num_dia = $dia;

                    if(!$model->save()){
                        $transaction->rollBack();
                        $response = new ResponseServices();
                        $response->status = 'error';

                        return $response;
                    }
                }catch(Exception $e) {
                    $transaction->rollBack();
                    $response = new ResponseServices();
                    $response->status = 'error';

                    return $response;
                } 
            }
            $transaction->commit();
            
            return $this->redirect(['index']);
        }
        $response = new ResponseServices();
        $response->status = 'error';

        return $response;
    }

    public function actionDeleteCp($id = null){
        if($id){
            $cp = $this->findModel($id);
            if($cp){
                if($cp->delete()){
                    return $this->redirect(['index']);
                }
            }
        }
    }
}
