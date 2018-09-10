<?php

namespace app\controllers;

use Yii;
use app\models\EntCitas;
use app\models\EntCitasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CatStatusCitas;
use app\models\CatTiposTramites;
use app\models\CatTiposClientes;
use app\models\CatTiposIdentificaciones;
use app\models\CatAreas;
use app\modules\ModUsuarios\models\Utils;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\models\Constantes;
use app\models\EntHistorialCambiosCitas;
use yii\data\ActiveDataProvider;
use app\components\AccessControlExtend;
use app\models\H2H;
use app\models\EntEnvios;
use app\models\ResponseServices;
use app\models\CatStatusCitasApi;
use yii\bootstrap\Html;
use yii\helpers\Url;
use app\models\Files;
use app\models\EntEvidenciasCitas;
use yii\web\UploadedFile;
use app\models\Calendario;
use app\models\CatCallsCenters;
use app\models\CatEquipos;
use app\models\RelMunicipioCodigoPostal;
use app\models\CatMunicipios;

/**
 * CitasController implements the CRUD actions for EntCitas model.
 */
class CitasController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'actualizar-envio-service') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControlExtend::className(),
                'only' => ['create', 'index', 'view', 'actualizar-envio', 'upload-file'],
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'view', 'actualizar-envio', 'upload-file'],
                        'allow' => true,
                        'roles' => [Constantes::USUARIO_CALL_CENTER],
                    ],
                    [
                        'actions' => ['guardar-archivos'],
                        'allow' => true,
                        'roles' => [Constantes::USUARIO_MASTER_BRIGHT_STAR],
                    ],


                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all EntCitas models.
     * @return mixed
     */
    public function actionIndex()
    {

        $statusCitas = CatStatusCitas::find()->where(['b_habilitado' => 1])->orderBy("txt_nombre")->all();



        $searchModel = new EntCitasSearch();
        $dataProvider = $searchModel->searchMes(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status' => $statusCitas,
        ]);
    }

    /**
     * Displays a single EntCitas model.
     * @param string $id
     * @return mixed
     */
    public function actionView($token)
    {

        $model = EntCitas::find()->where(['txt_token' => $token])->one();
        $model->scenario = "autorizar-update";

        $tiposTramites = CatTiposTramites::find()->where(['b_habilitado' => 1])->orderBy("txt_nombre")->all();
        $tiposClientes = CatTiposClientes::find()->where(['b_habilitado' => 1])->orderBy("txt_nombre")->all();
        $tiposIdentificaciones = CatTiposIdentificaciones::find()->where(['b_habilitado' => 1])->orderBy("txt_nombre")->all();


        if ($model->load(Yii::$app->request->post())) {
            // $equipo = CatEquipos::find()->where(["txt_nombre"=>$model->id_equipo])->one();
           
            // $model->id_equipo = $equipo->id_equipo;
            $model->fch_cita = Utils::changeFormatDateInput($model->fch_cita);

            if ($model->fch_nacimiento) {
                $model->fch_nacimiento = Utils::changeFormatDateInput($model->fch_nacimiento);
            }


            $model->setAddresCat();
            $municipio = RelMunicipioCodigoPostal::find()->where(["txt_codigo_postal" => $model->txt_codigo_postal])->one();
            if ($municipio) {
                $model->id_municipio = $municipio->id_municipio;
            }
            if ($model->isEdicion) {
                if ($model->save()) {
                    $model->guardarHistorialUpdate();
                    return $this->redirect(['index']);
                }

            } else {
                if (\Yii::$app->user->can(Constantes::USUARIO_SUPERVISOR)) {
                    $model->statusAprobacionDependiendoUsuario();
                    if (\Yii::$app->user->can(Constantes::USUARIO_ADMINISTRADOR_TELCEL)) {
                        $model->generarNumeroEnvio();
                        $model->setAutorizadaPor();
                    }

                    if ($model->save()) {
                        $model->guardarHistorialDependiendoUsuario();

                        return $this->redirect(['index']);
                    } else {
                        print_r($model->errors);
                    }
                }
            }

        }


        $model->fch_cita = Utils::changeFormatDate($model->fch_cita);
        if ($model->fch_nacimiento) {
            $model->fch_nacimiento = Utils::changeFormatDate($model->fch_nacimiento);
        }

        $historialCambios = $model->getEntHistorialCambiosCitas();

        $dataProvider = new ActiveDataProvider([
            'query' => $historialCambios
        ]);



        return $this->render('view', [
            'model' => $model,
            'tiposTramites' => $tiposTramites,
            'tiposClientes' => $tiposClientes,
            'tiposIdentificaciones' => $tiposIdentificaciones,

            'historial' => $dataProvider
        ]);
    }

    public function actionValidarFechaEntrega($token=null){
        $respuesta = new ResponseServices();
        if (\Yii::$app->user->can(Constantes::USUARIO_ADMINISTRADOR_TELCEL)) {
            $model = EntCitas::find()->where(['txt_token' => $token])->one();

            if($model->fch_entrega_equipo){
                $datetime1 = new \DateTime(Calendario::getFechaActual());
                $datetime2 = new \DateTime($model->fch_entrega_equipo);
                $interval = $datetime1->diff($datetime2);
                $numeroDias = $interval->format('%a');
    
                if($numeroDias>0){
                    $respuesta->status = "success";
                }
            }else{
                $respuesta->status = "success";
            }
            

        }else{
            $respuesta->status = "success";
        }

        return $respuesta;
    }



    /**
     * Creates a new EntCitas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        // $areaDefault = CatAreas::findOne(1); 
        // $idArea = $areaDefault->id_area;
        // $numServicios = $areaDefault->txt_dias_servicio;
        $tipoEntrega = 1;

        $usuario = EntUsuarios::getUsuarioLogueado();

        $model = new EntCitas(['scenario' => 'create-call-center']);

        if (\Yii::$app->user->can(Constantes::USUARIO_SUPERVISOR)) {
            $model = new EntCitas(['scenario' => 'autorizar']);
        }
        $model->iniciarModelo(1, null, $tipoEntrega);

        if ($model->load(Yii::$app->request->post())) {

            $municipio = RelMunicipioCodigoPostal::find()->where(["txt_codigo_postal" => $model->txt_codigo_postal])->one();
            if ($municipio) {
                $model->id_municipio = $municipio->id_municipio;
            }

            // $model->id_equipo = $equipo->id_equipo;
            // if($model->id_equipo==Constantes::SIN_EQUIPO){
            //     $model->b_documentos = 1;
            // }

            $model->fch_cita = Utils::changeFormatDateInput($model->fch_cita);
            if ($model->fch_nacimiento) {
                $model->fch_nacimiento = Utils::changeFormatDateInput($model->fch_nacimiento);
            }

            $model->fch_creacion = Utils::getFechaActual();
            $model->getConsecutivo();
            $model->statusAprobacionDependiendoUsuario();
            $model->setAddresCat();
            if ($model->save()) {

                if (\Yii::$app->user->can(Constantes::USUARIO_ADMINISTRADOR_TELCEL)) {
                    $model->generarNumeroEnvio();
                    $model->setAutorizadaPor();
                }
                if ($model->save()) {

                } else {

                }

                $model->guardarHistorialDependiendoUsuario(true);

                return $this->redirect(['index']);
            }

            $model->fch_cita = Utils::changeFormatDate($model->fch_cita);
            if ($model->fch_nacimiento) {
                $model->fch_nacimiento = Utils::changeFormatDate($model->fch_nacimiento);
            }
        }

        $tiposTramites = CatTiposTramites::find()->where(['b_habilitado' => 1])->orderBy("txt_nombre")->all();
        $tiposClientes = CatTiposClientes::find()->where(['b_habilitado' => 1])->orderBy("txt_nombre")->all();
        $tiposIdentificaciones = CatTiposIdentificaciones::find()->where(['b_habilitado' => 1])->orderBy("txt_nombre")->all();
        
       

        //$model->fch_cita = EntCitas::getFechaEntrega(Utils::getFechaActual());
        //$model->fch_cita = Utils::changeFormatDate($model->fch_cita);


        return $this->render('create', [
            'model' => $model,
            'tiposTramites' => $tiposTramites,
            'tiposClientes' => $tiposClientes,
            'tiposIdentificaciones' => $tiposIdentificaciones,


        ]);

    }

    /**
     * Updates an existing EntCitas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_cita]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing EntCitas model.
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
     * Finds the EntCitas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return EntCitas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntCitas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionCancelar($token = null)
    {
        $model = EntCitas::find()->where(['txt_token' => $token])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->statusCancelarDependiendoUsuario();
            if ($model->save()) {
                $model->guardarHistorialDependiendoUsuario(false, true);
                $this->redirect(["index"]);
            } else {
                print_r($model->errors);
                exit;
            }
        }
    }

    public function actionRechazar($token = null)
    {
        $model = EntCitas::find()->where(['txt_token' => $token])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->statusRechazarDependiendoUsuario();
            if ($model->save()) {
                $model->guardarHistorialDependiendoUsuario(false, false, true);
                $this->redirect(["index"]);
            } else {
                print_r($model->errors);
                exit;
            }
        }

    }


    public function actionValidarTelefono($tel = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $respuesta["status"] = "success";
        $respuesta["mensaje"] = "Teléfono disponible";
        $respuesta["tel"] = $tel;

        $telefonoDisponible = EntCitas::find()
            ->where(['txt_telefono' => $tel])
            ->andWhere(['in', 'id_status', [
                Constantes::STATUS_CREADA,
                Constantes::STATUS_AUTORIZADA_POR_SUPERVISOR,
                Constantes::STATUS_AUTORIZADA_POR_SUPERVISOR_TELCEL,
                Constantes::STATUS_AUTORIZADA_POR_ADMINISTRADOR_CC,
                // Constantes::STATUS_CANCELADA_SUPERVISOR_CC,
                // Constantes::STATUS_CANCELADA_SUPERVISOR_TELCEL,
                // Constantes::STATUS_CANCELADA_ADMINISTRADOR_CC,
                // Constantes::STATUS_CANCELADA_ADMINISTRADOR_TELCEL,
                Constantes::STATUS_AUTORIZADA_POR_ADMINISTRADOR_TELCEL,
                Constantes::STATUS_AUTORIZADA_POR_MASTER_BRIGHT_STAR,
                Constantes::STATUS_AUTORIZADA_POR_MASTER_TELCEL,
                Constantes::STATUS_AUTORIZADA_POR_MASTER_CALL_CENTER,
                // Constantes::STATUS_CANCELADA_POR_MASTER_BRIGHT_STAR,
                // Constantes::STATUS_CANCELADA_POR_MASTER_TELCEL,
                // Constantes::STATUS_CANCELADAS_POR_MASTER_CALL_CENTER,
                Constantes::STATUS_RECIBIDO_MENSAJERIA,
                Constantes::STATUS_LISTO_ENTREGA,
                Constantes::STATUS_ANOMALO,
                // Constantes::STATUS_ENTREGADO,
                // Constantes::STATUS_CANCELADO,
                // Constantes::STATUS_NO_ENTREGADO,
                Constantes::STATUS_NO_VISITADO,
                Constantes::STATUS_PRIMERA_VISITA,
                Constantes::STATUS_SEGUNDA_VISITA,
            ]])
            ->one();

        if ($telefonoDisponible) {
            $respuesta["status"] = "error";
            $respuesta["mensaje"] = "El número teléfonico " . $tel . " ya se encuentra en una cita activa: " . $telefonoDisponible->txt_identificador_cliente;
        }


        return $respuesta;

    }

    public function actionValidarImei($tel = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $respuesta["status"] = "success";
        $respuesta["mensaje"] = "IMEI disponible";
        $respuesta["tel"] = $tel;

        if (!$tel) {
            return $respuesta;
        }
        $telefonoDisponible = EntCitas::find()
            ->where(['txt_imei' => $tel])
            ->andWhere(['in', 'id_status', [
                Constantes::STATUS_CREADA,
                Constantes::STATUS_AUTORIZADA_POR_SUPERVISOR,
                Constantes::STATUS_AUTORIZADA_POR_SUPERVISOR_TELCEL,
                Constantes::STATUS_AUTORIZADA_POR_ADMINISTRADOR_CC,
                // Constantes::STATUS_CANCELADA_SUPERVISOR_CC,
                // Constantes::STATUS_CANCELADA_SUPERVISOR_TELCEL,
                // Constantes::STATUS_CANCELADA_ADMINISTRADOR_CC,
                // Constantes::STATUS_CANCELADA_ADMINISTRADOR_TELCEL,
                Constantes::STATUS_AUTORIZADA_POR_ADMINISTRADOR_TELCEL,
                Constantes::STATUS_AUTORIZADA_POR_MASTER_BRIGHT_STAR,
                Constantes::STATUS_AUTORIZADA_POR_MASTER_TELCEL,
                Constantes::STATUS_AUTORIZADA_POR_MASTER_CALL_CENTER,
                // Constantes::STATUS_CANCELADA_POR_MASTER_BRIGHT_STAR,
                // Constantes::STATUS_CANCELADA_POR_MASTER_TELCEL,
                // Constantes::STATUS_CANCELADAS_POR_MASTER_CALL_CENTER,
                Constantes::STATUS_RECIBIDO_MENSAJERIA,
                Constantes::STATUS_LISTO_ENTREGA,
                Constantes::STATUS_ANOMALO,
                // Constantes::STATUS_ENTREGADO,
                // Constantes::STATUS_CANCELADO,
                // Constantes::STATUS_NO_ENTREGADO,
                Constantes::STATUS_NO_VISITADO,
                Constantes::STATUS_PRIMERA_VISITA,
                Constantes::STATUS_SEGUNDA_VISITA,
            ]])
            ->one();

        if ($telefonoDisponible) {
            $respuesta["status"] = "error";
            $respuesta["mensaje"] = "El IMEI " . $tel . " ya se encuentra en una cita activa: " . $telefonoDisponible->txt_identificador_cliente;
        }


        return $respuesta;

    }

    public function actionValidarIccid($tel = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;



        $respuesta["status"] = "success";
        $respuesta["mensaje"] = "ICCID disponible";
        $respuesta["tel"] = $tel;

        if (!$tel) {
            return $respuesta;
        }

        $telefonoDisponible = EntCitas::find()
            ->where(['txt_iccid' => $tel])
            ->andWhere(['in', 'id_status', [
                Constantes::STATUS_CREADA,
                Constantes::STATUS_AUTORIZADA_POR_SUPERVISOR,
                Constantes::STATUS_AUTORIZADA_POR_SUPERVISOR_TELCEL,
                Constantes::STATUS_AUTORIZADA_POR_ADMINISTRADOR_CC,
                // Constantes::STATUS_CANCELADA_SUPERVISOR_CC,
                // Constantes::STATUS_CANCELADA_SUPERVISOR_TELCEL,
                // Constantes::STATUS_CANCELADA_ADMINISTRADOR_CC,
                // Constantes::STATUS_CANCELADA_ADMINISTRADOR_TELCEL,
                Constantes::STATUS_AUTORIZADA_POR_ADMINISTRADOR_TELCEL,
                Constantes::STATUS_AUTORIZADA_POR_MASTER_BRIGHT_STAR,
                Constantes::STATUS_AUTORIZADA_POR_MASTER_TELCEL,
                Constantes::STATUS_AUTORIZADA_POR_MASTER_CALL_CENTER,
                // Constantes::STATUS_CANCELADA_POR_MASTER_BRIGHT_STAR,
                // Constantes::STATUS_CANCELADA_POR_MASTER_TELCEL,
                // Constantes::STATUS_CANCELADAS_POR_MASTER_CALL_CENTER,
                Constantes::STATUS_RECIBIDO_MENSAJERIA,
                Constantes::STATUS_LISTO_ENTREGA,
                Constantes::STATUS_ANOMALO,
                // Constantes::STATUS_ENTREGADO,
                // Constantes::STATUS_CANCELADO,
                // Constantes::STATUS_NO_ENTREGADO,
                Constantes::STATUS_NO_VISITADO,
                Constantes::STATUS_PRIMERA_VISITA,
                Constantes::STATUS_SEGUNDA_VISITA,
            ]])
            ->one();

        if ($telefonoDisponible) {
            $respuesta["status"] = "error";
            $respuesta["mensaje"] = "El ICCID " . $tel . " ya se encuentra en una cita activa: " . $telefonoDisponible->txt_identificador_cliente;
        }


        return $respuesta;

    }

    public function actionTestApi()
    {
        $apiEnvio = new H2H();
        $cita = EntCitas::find()->where(["txt_telefono" => "5583046022"])->one();
        $cita->generarNumeroEnvio();
        $cita->save();
    }

    public function actionConsultar()
    {
        $cita = new EntCitas();
        echo $cita->consultarEnvio("SSYBS01031800012");
    }

    public function actionVerStatusEnvio($token = null)
    {

        $cita = new EntCitas();
        $envio = EntEnvios::find()->where(['txt_token' => $token])->one();

        if ($envio->txt_respuesta_api && $envio->txt_historial_api) {


        } else {
            $envio->txt_respuesta_api = $cita->consultarEnvio($envio->txt_tracking);

            $envio->txt_historial_api = ($cita->consultarHistorico($envio->txt_tracking));


        }

        $respuestaApi = json_decode($envio->txt_respuesta_api);
        $historico = json_decode($envio->txt_historial_api);

        if (isset($respuestaApi->Response) && $respuestaApi->Response == "Failure") {
            return $this->render("sin-envio-h2h", ["tracking" => $envio->txt_tracking]);
        }

        if ($cita->id_status == Constantes::STATUS_ENTREGADO) {
            $envio->fch_entrega = $respuestaApi->Fecha;
            $envio->b_cerrado = 1;
        }

        $envio->save();

        return $this->render("ver-status-envio", ['envio' => $envio, "respuestaApi" => $respuestaApi, "historico" => $historico]);
    }


    public function actionTestApiImage($tracking = null)
    {
        //$tracking = "BGR17041800BN0001";
        $cita = new EntCitas();
        $respuestaApi = ($cita->consultarEnvio($tracking));
        $historico = ($cita->consultarHistorico($tracking));

        echo $historico;
        echo $respuestaApi;
        exit;
        print_r($respuestaApi);

        print_r($historico);
        exit;

    }

    public function actionActualizarTodaInformacion()
    {
        $response = new ResponseServices();
        $envios = EntEnvios::find()->limit(300)->orderBy("id_envio desc")->all();
        foreach ($envios as $envioSearch) {
            $cita = $envioSearch->idCita;
            $envioSearch->txt_respuesta_api = $cita->consultarEnvio($envioSearch->txt_tracking);
            $envioSearch->txt_historial_api = $cita->consultarHistorico($envioSearch->txt_tracking);
            $respuestaApi = json_decode($envioSearch->txt_respuesta_api);


            if (!$statusApi = CatStatusCitas::find()->where(["txt_identificador_api" => $respuestaApi->ClaveEvento])->one()) {
                $response->message = "No se encontro el status del api en la base de datos";
                continue;
            }


            $cita->id_status = $statusApi->id_statu_cita;

            if ($cita->id_status == Constantes::STATUS_ENTREGADO) {
                $envioSearch->fch_entrega = $respuestaApi->Fecha;
                $envioSearch->b_cerrado = 1;
            }
            $envioSearch->save();


            if (!$cita->save(false)) {
                $response->message = "No se pudo guardar la cita";
                $response->result = $cita->errors;
                return $response;
            }

            $response->status = "success";
            $response->message = "Todo correcto";



        }

        return $response;
    }

    public function actionActualizarEnvio($envio)
    {
        $response = new ResponseServices();
        if (!$envioSearch = EntEnvios::find()->where(["txt_tracking" => $envio])->one()) {
            $response->message = "No se encontro el envio en la base de datos";
            return $response;
        }
        $cita = $envioSearch->idCita;
        $envioSearch->txt_respuesta_api = $cita->consultarEnvio($envio);
        $envioSearch->txt_historial_api = $cita->consultarHistorico($envioSearch->txt_tracking);
        $respuestaApi = json_decode($envioSearch->txt_respuesta_api);

        if (!$statusApi = CatStatusCitas::find()->where(["txt_identificador_api" => $respuestaApi->ClaveEvento])->one()) {
            $response->message = "No se encontro el status del api en la base de datos";
            return $response;
        }


        $cita->id_status = $statusApi->id_statu_cita;

        if ($cita->id_status == Constantes::STATUS_ENTREGADO) {
            $envioSearch->fch_entrega = $respuestaApi->Fecha;
            $envioSearch->b_cerrado = 1;
        }
        $envioSearch->save();


        if (!$cita->save(false)) {
            $response->message = "No se pudo guardar la cita";
            $response->result = $cita->errors;
            return $response;
        }

        $response->status = "success";
        $response->message = "Todo correcto";
        $statusColor = EntCitas::getColorStatus($cita->id_status);
        $response->result["a"] = Html::a(
            $statusApi->txt_nombre,
            Url::to(['citas/view', 'token' => $cita->txt_token]),
            [
                'id' => "js-cita-envio-" . $cita->txt_token,
                'data-envio' => $envio,
                'class' => 'btn badge ' . $statusColor . ' no-pjax actualizar-envio',
            ]
        );
        $response->result["token"] = $cita->txt_token;

        return $response;
    }

    public function actionUploadFile($token = null)
    {
        $cita = EntCitas::find()->where(["txt_token" => $token])->one();
        $evidencia = EntEvidenciasCitas::find()->where(["id_cita" => $cita->id_cita])->one();

        if ($evidencia) {
            Files::borrarArchivo($evidencia->txt_url);
        } else {
            $evidencia = new EntEvidenciasCitas();
        }

        $response = new ResponseServices();

        $file = UploadedFile::getInstanceByName("file-upload");
        //$response->result = $file;

        if (!$file) {
            $response->message = "Archivo nulo";
            return $response;
        }



        $namefile = $cita->txt_telefono . "." . $file->extension;
        $path = $cita->pathBaseEvidencia . $namefile;
        $isSaved = $file->saveAs($path);

        if ($isSaved) {

            $evidencia->id_cita = $cita->id_cita;
            $evidencia->txt_url = $path;
            $evidencia->txt_nombre_original = $file->name;
            $evidencia->txt_token = Utils::generateToken("FIL");
            $evidencia->fch_creacion = Calendario::getFechaActual();

            if ($evidencia->save()) {
                $response->message = "Archivo guardado.";
                $response->status = "success";
                $response->result['url'] = Url::base() . "/citas/descargar-evidencia?token=" . $cita->txt_identificador_cliente;
            } else {
                $response->message = "Ocurrio un problema al guardar en la base de datos.";
                Files::borrarArchivo($path);
            }


        } else {
            $response->message = "El archivo no se pudo guardar.";
        }

        return $response;
    }

    public function actionDescargarEvidencia($token = null)
    {
        $cita = EntCitas::find()->where(["txt_identificador_cliente" => $token])->one();

        if (empty($cita)) {
            $evidencia = EntEvidenciasCitas::find()->where(["txt_token" => $token])->one();
            $cita = $evidencia->idCita;
            if ($evidencia) {
                $ubicacionArchivo = $evidencia->txt_url;
            } else {
                $ubicacionArchivo = "";
            }

        } else {
            $ubicacionArchivo = $cita->pathBaseEvidencia . $cita->txt_telefono . ".pdf";
        }


        if (file_exists($ubicacionArchivo)) {
            Yii::$app->response->sendFile($ubicacionArchivo, "Evidencia_" . $cita->txt_telefono . ".pdf");
        } else {
            echo "No existe el archivo para descargar: " . $ubicacionArchivo;
        }

    }



    public function actionEnvios()
    {
    //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //$envios = EntEnvios::find()->select("txt_respuesta_api")->all();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

// create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        $items = Yii::$app->db->createCommand('select 
     EN.txt_historial_api
       from ent_citas C
    LEFT JOIN cat_tipos_tramites TT ON TT.id_tramite = C.id_tipo_tramite
    LEFT JOIN cat_equipos E ON E.id_equipo = C.id_equipo
    LEFT JOIN cat_areas A ON A.id_area = C.id_area
    LEFT JOIN mod_usuarios_ent_usuarios U ON U.id_usuario = C.id_usuario
    LEFT JOIN cat_status_citas SC ON SC.id_statu_cita = C.id_status
    LEFT JOIN cat_tipos_clientes TC ON TC.id_tipo_cliente = C.id_tipo_cliente
    LEFT JOIN cat_tipos_identificaciones TI ON TI.id_tipo_identificacion = C.id_tipo_identificacion
    LEFT JOIN ent_horarios_areas HA ON HA.id_horario_area = C.id_horario
    LEFT JOIN cat_cats CC ON CC.id_cat = C.id_cat
    LEFT JOIN ent_envios EN ON EN.id_envio = C.id_envio;
    
    
    ');

        $items = $items->query();


        foreach ($items as $row) {
            fputcsv($output, $row);
        }


    }

    public function actionTestCrear()
    {
        $fichero = 'evidencias/test/gente.txt';
// Abre el fichero para obtener el contenido existente
        $actual = file_get_contents($fichero);
// Añade una nueva persona al fichero
        $actual .= "John Smith\n";
// Escribe el contenido al fichero


        file_put_contents($fichero, $actual);
    }

    public function actionCrearPass()
    {
        $usuario = new EntUsuarios();
        $usuario->setPassword("springer");

        echo $usuario->txt_password_hash;
        exit;
    }

    public function actionExportar()
    {

        $modelSearch = new EntCitasSearch();
        $dataProvider = $modelSearch->searchExport(Yii::$app->request->queryParams);


        return $this->render("exportar", ["dataProvider" => $dataProvider, "modelSearch" => $modelSearch]);
    }

    public function actionDownloadData()
    {

        $modelSearch = new EntCitasSearch();
        $dataProvider = $modelSearch->searchExport(Yii::$app->request->queryParams);

        if (Yii::$app->request->isGet) :
            //The name of the CSV file that will be downloaded by the user.
        $fileName = 'Reporte.csv';
        $data = [];
        $data[0] = $this->setHeadersCsvT();
        foreach ($dataProvider->getModels() as $key => $modelo) :

            $intentos = 0;
        if ($modelo->idEnvio) :

            if ($modelo->idEnvio->txt_historial_api) :
            $json = json_decode($modelo->idEnvio->txt_historial_api);

        if (isset($json->History)) :
            foreach ($json->History as $llave => $historial) :
            if ($historial->EventoClave == 11) {
            $intentos++;
        }
        if ($historial->EventoClave == 12) {
            $intentos++;
        }

        if ($historial->EventoClave == 5) {
            $intentos++;
        }
        endforeach;
        endif;

        endif;

        endif;

        $estatusEntrega = "";
        if ($modelo->idStatus) {
            if ($modelo->idStatus->txt_identificador_api) {
                $estatusEntrega = $modelo->idStatus->txt_nombre;
            }
        }

        $horario = "";
        if ($modelo->b_entrega_cat && $modelo->id_cat) {
            $horario = $modelo->txt_horario_entrega_cat;
        } else {
            $horario = $modelo->idHorario ? $modelo->idHorario->txt_hora_inicial . " - " . $modelo->idHorario->txt_hora_final : "";
        }

        $cac = "DOMICILIO";
        if ($modelo->b_entrega_cat && $modelo->id_cat) {
            $cac = "CAC - " . $modelo->idCat->txt_nombre;
        } else if ($modelo->b_entrega_cat) {
            $cac = "CAC - ";
        }

        $data[$modelo->id_cita] = [
            $modelo->txt_identificador_cliente,
            $modelo->txt_telefono,
            $modelo->idEnvio ? $modelo->idEnvio->txt_tracking : '',
            $modelo->idArea ? $modelo->idArea->txt_nombre : '',
            $modelo->idMunicipio ? $modelo->idMunicipio->idTipo->txt_nombre : '',
            $modelo->idMunicipio ? $modelo->idMunicipio->diasServicio : "",
            $modelo->idTipoTramite->txt_nombre,
            $cac,
            $modelo->txt_calle_numero,
            $modelo->txt_colonia,
            $modelo->txt_municipio,
            $modelo->txt_estado,
            $modelo->txt_codigo_postal,
            $modelo->txt_entre_calles,
            $modelo->txt_observaciones_punto_referencia,
            $modelo->idCallCenter ? $modelo->idCallCenter->txt_nombre : '',
            Utils::changeFormatDateInputShort($modelo->fch_creacion),
            Utils::changeFormatDateInputShort($modelo->fch_cita),
            $horario,
            $modelo->txt_autorizado_por,
            $intentos,
            $estatusEntrega,

            $modelo->nombreCompleto,
            $modelo->txt_sap_equipo,
            $modelo->txt_equipo,
            "'" . $modelo->txt_imei,
            $modelo->txt_sap_iccid,
            "'" . $modelo->txt_iccid,
            $modelo->txt_sap_promocional,
            $modelo->txt_promocional,
            $modelo->txt_sap_promocional_2,
            $modelo->txt_promocional_2,
            $modelo->txt_sap_promocional_3,
            $modelo->txt_promocional_3,
            $modelo->txt_sap_promocional_4,
            $modelo->txt_promocional_4,
            $modelo->txt_sap_promocional_5,
            $modelo->txt_promocional_5,
            $modelo->txt_tpv,


        ];

        $historico = [];


        $data[$modelo->id_cita] = array_merge($data[$modelo->id_cita], $historico);

        endforeach;

            // print_r($historico);
            // exit;
            

            
            //Set the Content-Type and Content-Disposition headers.

        header('Content-Type: application/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
    
            //Open up a PHP output stream using the function fopen.
        $fp = fopen('php://output', 'w');
        //add BOM to fix UTF-8 in Excel
        fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        
            //Loop through the array containing our CSV data.
        foreach ($data as $row) {
            //fputcsv formats the array into a CSV format.
            //It then writes the result to our output stream.
            fputcsv($fp, $row);
        }
    
            //Close the file handle.
        fclose($fp);
        exit;
        endif;

        return $this->render("exportar", ["dataProvider" => $dataProvider, "modelSearch" => $modelSearch]);
    }



    public function setHeadersCsvT()
    {


        return [
            "Identificador único", "Número celular", "Identificador de envio", "Area", "Tipo / Zona",
            "Frecuencia", "Trámite", "En", "Calle", "Colonia", "Municipio", "Estado", "C.P.", "Entre calles", "Referencias", "Fza Vta", "Captura",
            "CitaOrig", "HoraOrig", "EstatusCita", "IntentosEntrega", "EstatusEntrega", "Cliente", "SAP Equipo", "Equipo", "IMEI",
            "SAP ICCID", "ICCID", "SAP Promocional 1", "Promocional", "SAP Promocional 2", "Promocional 2", "SAP Promocional 3", "Promocional 3", "SAP Promocional 4",
            "Promocional 4", "SAP Promocional 5", "Promocional 5", "TPV"
        ];
    }

    public function setHeadersCsvB()
    {


        return [
            "Identificador único", "Número celular", "Identificador de envio", "Area", "Tipo / Zona",
            "Frecuencia", "Trámite", "En", "Calle", "Colonia", "Municipio", "Estado", "C.P.", "Entre calles", "Referencias", "Fza Vta", "Captura",
            "CitaOrig", "HoraOrig", "EstatusCita", "IntentosEntrega", "EstatusEntrega", "Cliente", "SAP Equipo", "Equipo", "IMEI",
            "SAP ICCID", "ICCID", "SAP Promocional 1", "Promocional", "SAP Promocional 2", "Promocional 2", "SAP Promocional 3", "Promocional 3", "SAP Promocional 4",
            "Promocional 4", "SAP Promocional 5", "Promocional 5", "TPV", "Fecha entrega B"
        ];
    }

    public function actionDownloadDataCitasEnvio(){
       

        $modelSearch = new EntCitasSearch();
        $dataProvider = $modelSearch->searchExport(Yii::$app->request->queryParams);

        if (Yii::$app->request->isGet) :
            //The name of the CSV file that will be downloaded by the user.
        $fileName = 'Reporte.csv';
        $data = [];
        $data[0] = $this->setHeadersCsvB();
        foreach ($dataProvider->getModels() as $key => $modelo) :

            $intentos = 0;
        if ($modelo->idEnvio) :

            if ($modelo->idEnvio->txt_historial_api) :
            $json = json_decode($modelo->idEnvio->txt_historial_api);

        if (isset($json->History)) :
            foreach ($json->History as $llave => $historial) :
            if ($historial->EventoClave == 11) {
            $intentos++;
        }
        if ($historial->EventoClave == 12) {
            $intentos++;
        }

        if ($historial->EventoClave == 5) {
            $intentos++;
        }
        endforeach;
        endif;

        endif;

        endif;

        $estatusEntrega = "";
        if ($modelo->idStatus) {
            if ($modelo->idStatus->txt_identificador_api) {
                $estatusEntrega = $modelo->idStatus->txt_nombre;
            }
        }

        $horario = "";
        if ($modelo->b_entrega_cat && $modelo->id_cat) {
            $horario = $modelo->txt_horario_entrega_cat;
        } else {
            $horario = $modelo->idHorario ? $modelo->idHorario->txt_hora_inicial . " - " . $modelo->idHorario->txt_hora_final : "";
        }

        $cac = "DOMICILIO";
        if ($modelo->b_entrega_cat && $modelo->id_cat) {
            $cac = "CAC - " . $modelo->idCat->txt_nombre;
        } else if ($modelo->b_entrega_cat) {
            $cac = "CAC - ";
        }

        $data[$modelo->id_cita] = [
            $modelo->txt_identificador_cliente,
            $modelo->txt_telefono,
            $modelo->idEnvio ? $modelo->idEnvio->txt_tracking : '',
            $modelo->idArea ? $modelo->idArea->txt_nombre : '',
            $modelo->idMunicipio ? $modelo->idMunicipio->idTipo->txt_nombre : '',
            $modelo->idMunicipio ? $modelo->idMunicipio->diasServicio : "",
            $modelo->idTipoTramite->txt_nombre,
            $cac,
            $modelo->txt_calle_numero,
            $modelo->txt_colonia,
            $modelo->txt_municipio,
            $modelo->txt_estado,
            $modelo->txt_codigo_postal,
            $modelo->txt_entre_calles,
            $modelo->txt_observaciones_punto_referencia,
            $modelo->idCallCenter ? $modelo->idCallCenter->txt_nombre : '',
            Utils::changeFormatDateInputShort($modelo->fch_creacion),
            Utils::changeFormatDateInputShort($modelo->fch_cita),
            $horario,
            $modelo->txt_autorizado_por,
            $intentos,
            $estatusEntrega,

            $modelo->nombreCompleto,
            $modelo->txt_sap_equipo,
            $modelo->txt_equipo,
            "'" . $modelo->txt_imei,
            $modelo->txt_sap_iccid,
            "'" . $modelo->txt_iccid,
            $modelo->txt_sap_promocional,
            $modelo->txt_promocional,
            $modelo->txt_sap_promocional_2,
            $modelo->txt_promocional_2,
            $modelo->txt_sap_promocional_3,
            $modelo->txt_promocional_3,
            $modelo->txt_sap_promocional_4,
            $modelo->txt_promocional_4,
            $modelo->txt_sap_promocional_5,
            $modelo->txt_promocional_5,
            $modelo->txt_tpv,
            Utils::changeFormatDateInputShort($modelo->fch_entrega_equipo),


        ];

        $historico = [];


        $data[$modelo->id_cita] = array_merge($data[$modelo->id_cita], $historico);

        endforeach;

            // print_r($historico);
            // exit;
            

            
            //Set the Content-Type and Content-Disposition headers.

        header('Content-Type: application/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
    
            //Open up a PHP output stream using the function fopen.
        $fp = fopen('php://output', 'w');
        //add BOM to fix UTF-8 in Excel
        fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        
            //Loop through the array containing our CSV data.
        foreach ($data as $row) {
            //fputcsv formats the array into a CSV format.
            //It then writes the result to our output stream.
            fputcsv($fp, $row);
        }
    
            //Close the file handle.
        fclose($fp);
        exit;
        endif;

        return $this->render("exportar", ["dataProvider" => $dataProvider, "modelSearch" => $modelSearch]);
    
    }

    public function setHeadersCsv()
    {
        return [
            "Identificador único", "Número celular", "Identificador de envio", "Tipo / Zona",
            "Frecuencia", "Trámite", "En", "Fza Vta", "Captura",
            "CitaOrig", "HoraOrig", "EstatusCita", "IntentosEntrega", "EstatusEntrega", "Cliente", "Equipo", "IMEI",
            "ICCID", "Promocional", "Promocional 2", "Promocional 3", "Promocional 4", "Promocional 5", "TPV", "Calle y número", "Colonia", "Municipio",
            "Estado", "C.P.", "Referencias", "Fecha de Entregado", "FECHA_1", "HORA_1", "NOTA_1", "EXCEPCION_1", "Operador", "FECHA_2", "NOTA_2", "EXCEPCION_2",
            "Operador", "FECHA_3", "NOTA_3", "EXCEPCION_3", "Operador", "Fecha de solicitud de devolución", "Motivo de devolucion", "Excepción final", "Fecha de excepción final"
        ];
    }

    public function actionDownloadDataCitas()
    {

        $modelSearch = new EntCitasSearch();
        $dataProvider = $modelSearch->searchExport(Yii::$app->request->queryParams);

        if (Yii::$app->request->isGet) :
            //The name of the CSV file that will be downloaded by the user.
        $fileName = 'Reporte.csv';
        $data = [];
        $data[0] = $this->setHeadersCsv();
        foreach ($dataProvider->getModels() as $key => $modelo) :
                
            $intentos = 0;
            if ($modelo->idEnvio) :
    
                if ($modelo->idEnvio->txt_historial_api) :
                $json = json_decode($modelo->idEnvio->txt_historial_api);
    
            if (isset($json->History)) :
                foreach ($json->History as $llave => $historial) :
                if ($historial->EventoClave == 11) {
                $intentos++;
            }
            if ($historial->EventoClave == 12) {
                $intentos++;
            }
    
            if ($historial->EventoClave == 5) {
                $intentos++;
            }
            endforeach;
            endif;
    
            endif;
    
            endif;
    
            $estatusEntrega = "";
            if ($modelo->idStatus) {
                if ($modelo->idStatus->txt_identificador_api) {
                    $estatusEntrega = $modelo->idStatus->txt_nombre;
                }
            }
    
            $horario = "";
            if ($modelo->b_entrega_cat && $modelo->id_cat) {
                $horario = $modelo->txt_horario_entrega_cat;
            } else {
                $horario = $modelo->idHorario ? $modelo->idHorario->txt_hora_inicial . " - " . $modelo->idHorario->txt_hora_final : "";
            }
    
            $cac = "DOMICILIO";
            if ($modelo->b_entrega_cat && $modelo->id_cat) {
                $cac = "CAC - " . $modelo->idCat->txt_nombre;
            } else if ($modelo->b_entrega_cat) {
                $cac = "CAC - ";
            }

            $fechaEntrega = "";
            $fecha1 = "";
            $hora1 = "";
            $nota1 = "";
            $exepcion1 = "";
            $operador = "";
            $fecha2 = "";
            $nota2 = "";
            $excepcion2 = "";
            $operador2 = "";
            $fecha3 = "";
            $nota3 = "";
            $excepcion3 = "";
            $operador3 = "";
            $fchSolicitudDevolucion = "";
            $motivoDevolucion = "";
            $excepcionFinal = "";
            $fechaExcepcionFinal = "";

            if ($modelo->idEnvio) {
            
                if ($modelo->idEnvio->txt_historial_api) {
                    $json = json_decode($modelo->idEnvio->txt_historial_api);
                
                    if (isset($json->History)) {
                        foreach ($json->History as $llave => $historial){

                            // Si ya esta entregado
                            if ($historial->EventoClave == Constantes::STATUS_API_ENTREGADO) {
                                $fechaEntrega = $historial->Fecha;
                                if($intentos==1){
                                    $fecha1 = Calendario::getDateSimple($historial->Fecha);
                                    $hora1 = Calendario::getHoursMinutes($historial->Fecha);
                                    $nota1 = $historial->Comentario;
                                }

                                if($intentos==2){
                                    $fecha2 = Calendario::getDateSimple($historial->Fecha);
                                    $nota2 = $historial->Comentario;
                                    $excepcion2 = $historial->Evento;
                                }

                                if($intentos==3){
                                    $fecha3 = Calendario::getDateSimple($historial->Fecha);
                                    $nota3 = $historial->Comentario;
                                    $excepcion3 = $historial->Evento;
                                }
                            }

                            // Si es primera visita
                            if($historial->EventoClave == Constantes::STATUS_API_PRIMERA_VISITA){
                                $fecha1 = Calendario::getDateSimple($historial->Fecha);
                                $hora1 = Calendario::getHoursMinutes($historial->Fecha);
                                $nota1 = $historial->Comentario;
                                $exepcion1 = $historial->Evento;
                            }

                            // Si es segunda visita
                            if($historial->EventoClave == Constantes::STATUS_API_SEGUNDA_VISITA){
                                $fecha2 = Calendario::getDateSimple($historial->Fecha);
                                $nota2 = $historial->Comentario;
                                $exepcion2 = $historial->Evento;
                            }

                            if($historial->EventoClave== Constantes::STATUS_API_DEVOLUCION_EN_TRANSITO){
                                $fchSolicitudDevolucion = Calendario::getDateSimple($historial->Fecha);
                                $motivoDevolucion = $historial->Comentario;
                            }

                            
                        }
                    }
    
                }

                if($modelo->idEnvio->txt_respuesta_api){
                    $eventoFinal = json_decode($modelo->idEnvio->txt_respuesta_api);
                
                    if (isset($eventoFinal->Response)) {
                        if($eventoFinal->ClaveEvento==Constantes::STATUS_API_ENTREGADO || $eventoFinal->ClaveEvento==Constantes::STATUS_API_DEVUELTO || $eventoFinal->ClaveEvento==Constantes::STATUS_API_INCIDENCIA){
                            $excepcionFinal = $eventoFinal->Evento;
                            $fechaExcepcionFinal = Calendario::getDateSimple($eventoFinal->Fecha);
                        }
                    }
                }
    
            }
        

        $data[$modelo->id_cita] = [
            $modelo->txt_identificador_cliente,
            $modelo->txt_telefono,

            $modelo->idEnvio ? $modelo->idEnvio->txt_tracking : '',
            $modelo->idMunicipio ? $modelo->idMunicipio->idTipo->txt_nombre : '',
            $modelo->idMunicipio ? $modelo->idMunicipio->diasServicio : "",
            $modelo->idTipoTramite->txt_nombre,
            $modelo->b_entrega_cat ? "CAC" : "Domicilio",
            $modelo->idCallCenter ? $modelo->idCallCenter->txt_nombre : '',
            Utils::changeFormatDateInputShort($modelo->fch_creacion),
            Utils::changeFormatDateInputShort($modelo->fch_cita),
            $modelo->idHorario ? $modelo->idHorario->txt_hora_inicial . " - " . $modelo->idHorario->txt_hora_final : "",
            $modelo->idStatus ? $modelo->idStatus->txt_nombre : '',
            $intentos,
            $estatusEntrega,
            $modelo->nombreCompleto,
            $modelo->txt_equipo,
            $modelo->txt_imei,
            $modelo->txt_iccid,
            $modelo->promocional1,
            $modelo->promocional2,
            $modelo->promocional3,
            $modelo->promocional4,
            $modelo->promocional5,
            $modelo->txt_tpv,
            $modelo->txt_calle_numero,
            $modelo->txt_colonia,
            $modelo->txt_municipio,
            $modelo->txt_estado,
            $modelo->txt_codigo_postal,
            $modelo->txt_entre_calles,
            $fechaEntrega,
            $fecha1,
            $hora1,
            $nota1,
            $exepcion1,
            $operador,
            $fecha2,
            $nota2,
            $excepcion2,
            $operador2,
            $fecha3,
            $nota3,
            $excepcion3,
            $operador3,
            $fchSolicitudDevolucion,
            $motivoDevolucion,
            $excepcionFinal,
            $fechaExcepcionFinal,
        ];

        // "Identificador único", "Número celular", "Identificador de envio", "Tipo / Zona",
        //     "Frecuencia", "Trámite", "En", "Fza Vta", "Captura",
        //     "CitaOrig", "HoraOrig", "EstatusCita", "IntentosEntrega", "EstatusEntrega", "Cliente", "Equipo", "IMEI",
        //     "ICCID", "Promocional", "Promocional 2", "Promocional 3", "Promocional 4", "Promocional 5", "TPV", "Calle y número", "Colonia", "Municipio",
        //     "Estado", "C.P.", "Referencias", "Fecha de Entregado", "FECHA_1", "HORA_1", "NOTA_1", "EXCEPCION_1", "Operador", "FECHA_2", "NOTA_2", "EXCEPCION_2",
        //     "Operador", "FECHA_3", "NOTA_3", "EXCEPCION_3", "Operador", "Fecha de solicitud de devolución", "Motivo de devolucion", "Excepción final", "Fecha de excepción final"

        
       
        

        endforeach;

            // print_r($historico);
            // exit;
            

            
            //Set the Content-Type and Content-Disposition headers.

        header('Content-Type: application/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
    
            //Open up a PHP output stream using the function fopen.
        $fp = fopen('php://output', 'w');
        //add BOM to fix UTF-8 in Excel
        fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            //Loop through the array containing our CSV data.
        foreach ($data as $row) {
            //fputcsv formats the array into a CSV format.
            //It then writes the result to our output stream.
            fputcsv($fp, $row);
        }
    
            //Close the file handle.
        fclose($fp);
        exit;
        endif;

        return $this->render("exportar", ["dataProvider" => $dataProvider, "modelSearch" => $modelSearch]);
    }

   

    /**
     * Actualiza el envio
     */
    public function actionActualizarEnvioService()
    {

        $response = new ResponseServices();
        try {
            $headers = Yii::$app->request->headers;
       
        // returns the Accept header value
            $auth = $headers->get('authentication-token');

            if ($auth != "sGbMY0YViRodBMibIHLyjz2QZckDSEPm0+MPwQ3gI7u") {
                $response->message = "Se necesita un token de acceso válido";

                return $response;
            }


            if (!file_get_contents("php://input")) {
                $response->message = "No se envio la información";
                return $response;
            }

            $json = json_decode(file_get_contents("php://input"));

            if (!isset($json->tracking)) {
                $response->message = "No se envio el parametro de tracking";
                return $response;
            }

            $tracking = $json->tracking;
            Yii::info($json->tracking, 'peticiones');

        // BSMH-300518-3
        // BGR30041801LA0236
            $envioSearch = EntEnvios::find()->where(["txt_tracking" => $tracking])->one();

            if (!$envioSearch) {
                $response->message = "No existe el tracking en la base de datos.";
                return $response;
            }

            $cita = $envioSearch->idCita;

            if (!$cita) {
                $response->message = "El envio no tiene una cita asignada";
                return $response;
            }

            $envioSearch->txt_respuesta_api = $cita->consultarEnvio($tracking);
            $envioSearch->txt_historial_api = $cita->consultarHistorico($envioSearch->txt_tracking);
            $respuestaApi = json_decode($envioSearch->txt_respuesta_api);

            if (!$statusApi = CatStatusCitas::find()->where(["txt_identificador_api" => $respuestaApi->ClaveEvento])->one()) {
                $response->message = "No se encontro el status del api en la base de datos";
                return $response;
            }


            $cita->id_status = $statusApi->id_statu_cita;

            if ($cita->id_status == Constantes::STATUS_ENTREGADO) {
                $envioSearch->fch_entrega = $respuestaApi->Fecha;
                $envioSearch->b_cerrado = 1;
            }

            if (!$envioSearch->save()) {
                $response->message = "No se pudo actualizar el envio";
                $response->result = $envioSearch->errors;
                return $response;
            }


            if (!$cita->save(false)) {
                $response->message = "No se pudo actualizar la cita";
                $response->result = $cita->errors;
                return $response;
            }

            $response->status = "success";
            $response->message = "Actualización correcta";


            return $response;
        } catch (\Exception $e) {
            $response->message = "Ocurrio un problema con el servidor. Si el problema persiste comunicarse con 2 Geeks one Monkey";
            return $response;
        }
    }

    public function actionSubirArchivos()
    {



        return $this->render("subir-archivos");

    }

    public function actionGuardarArchivos()
    {
        $response = new ResponseServices();

        $archivo = UploadedFile::getInstanceByName("file");

        if (!$archivo) {
            $response->message = "No hay archivos";
            return $response;
        }

        if (isset($_POST["fecha"]) && $_POST["fecha"]) {
            $anio = Calendario::getYearLastDigit($_POST["fecha"]);
            $mes = Calendario::getMonthNumber($_POST["fecha"]);
        } else {
            $anio = Calendario::getYearLastDigit();
            $mes = Calendario::getMonthNumber();
        }


        $pathBase = "evidencias/";

        $pathAnio = $pathBase . $anio . "/";
        Files::validarDirectorio($pathAnio);

        $pathMes = $pathAnio . $mes . "/";
        Files::validarDirectorio($pathMes);

        if ($archivo->saveAs($pathMes . $archivo->baseName . '.' . $archivo->extension)) {
            $response->status = "success";
            $response->message = "Archivo guardado";
        } else {
            $response->message = "No se pudo guardar el archivo";
        }

        return $response;

    }

    public function actionImportDataMunicipios(){

        if(Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $file = UploadedFile::getInstanceByName('file-import');
            
            if($file){
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

                $spreadsheet = $reader->load($file->tempName);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();

                foreach($sheetData as  $key => $data){
                    if($key == 0)
                        continue;

                    
                    $mun = CatMunicipios::find()->where(['id_municipio'=>$data[0]])->one();
                    if($mun){
                        $mun->id_tipo = $data[2];
                        $mun->id_area = $data[3];
                        $mun->b_lunes = $data[4];
                        $mun->b_martes = $data[5];
                        $mun->b_miercoles = $data[6];
                        $mun->b_jueves = $data[7];
                        $mun->b_viernes = $data[8];
                        $mun->b_sabado = $data[9];
                        $mun->b_domingo = $data[10];

                        if(!$mun->save()){
                            return [
                                'status' => 'error',
                                'message' => 'No se guardaron los datos del municipio'
                            ];
                        }
                    }else{
                        return [
                            'status' => 'error',
                            'message' => 'No existe el municipio'
                        ];
                    }
                }

                return [
                    'status' => 'success'
                ];
            }else{

                return [
                    'status' => 'error',
                    'message' => 'No hay archivo'
                ];
            }
        }

        return $this->render('datos_mun');
    }
}
