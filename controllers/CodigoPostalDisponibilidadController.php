<?php

namespace app\controllers;

use Yii;
use app\models\EntCodigoPostalDisponibilidad;
use app\models\EntCodigoPostalDisponibilidadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CatMunicipios;
use app\models\CatMunicipiosSearch;
use app\models\CatAreasSearch;
use app\models\CatAreas;
use yii\helpers\ArrayHelper;

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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all EntCodigoPostalDisponibilidad models.
     * @return mixed
     */
    public function actionIndex()
    {   
        $areas= CatAreas::find()->where(["b_habilitado"=>1])->orderBy("txt_nombre")->all();
        $areas = ArrayHelper::map($areas, "id_area", "txt_nombre");

        $municipios = CatMunicipios::find()->orderBy("txt_nombre")->all();
        $municipios = ArrayHelper::map($municipios, "id_municipio", "txt_nombre");

        $searchModel = new EntCodigoPostalDisponibilidadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            "municipios"=>$municipios,
            "areas"=>$areas
        ]);
    }

    /**
     * Displays a single EntCodigoPostalDisponibilidad model.
     * @param integer $id
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
     * @param integer $id
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
     * @param integer $id
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
     * @param integer $id
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

    public function actionBuscarMunicipio($q=null, $page=0){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $searchModel = new CatMunicipiosSearch();
        $searchModel->txt_nombre = $q;

        if($page > 1){
            $page--;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $response['results']= null;
        $response['total_count'] = $dataProvider->getTotalCount();

        $resultados = $dataProvider->getModels();
        if(count($resultados)==0){
            $response['results'][0] = ['id'=>'', "txt_nombre"=>''];
        }

        foreach($resultados as $model){
            $response['results'][]=['id'=>$model->id_municipio, "txt_nombre"=>$model->txt_nombre];
        }
    
        return $response;
    }
    public function actionBuscarArea($q=null, $page=0){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $searchModel = new CatAreasSearch();
        $searchModel->txt_nombre = $q;

        if($page > 1){
            $page--;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $response['results']= null;
        $response['total_count'] = $dataProvider->getTotalCount();

        $resultados = $dataProvider->getModels();
        if(count($resultados)==0){
            $response['results'][0] = ['id'=>'', "txt_nombre"=>''];
        }

        foreach($resultados as $model){
            $response['results'][]=['id'=>$model->id_area, "txt_nombre"=>$model->txt_nombre];
        }
    
        return $response;
    }

    public function actionHabilitarHorario($id){
        $registro = EntCodigoPostalDisponibilidad::find()->where(["id_disponiblidad"=>$id])->one();
        $registro->b_habilitado = 1;
        if(!$registro->save()){
            print_r($registro->errors);
           }
    }

    public function actionDeshabilitarHorario($id){
        $registro = EntCodigoPostalDisponibilidad::find()->where(["id_disponiblidad"=>$id])->one();
        $registro->b_habilitado = 0;
       if(!$registro->save()){
        print_r($registro->errors);
       }

    }
}
