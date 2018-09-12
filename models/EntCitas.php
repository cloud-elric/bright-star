<?php

namespace app\models;

use Yii;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\modules\ModUsuarios\models\Utils;
use yii\helpers\Html;
use yii\db\Expression;

/**
 * This is the model class for table "ent_citas".
 *
 * @property string $id_cita
 * @property string $id_tipo_tramite
 * @property string $id_equipo
 * @property string $id_area
 * @property string $id_tipo_entrega
 * @property string $id_usuario
 * @property string $id_status
 * @property string $id_envio
 * @property string $id_tipo_cliente
 * @property string $id_tipo_identificacion
 * @property string $id_horario
 * @property string $txt_telefono
 * @property string $txt_nombre
 * @property string $txt_apellido_paterno
 * @property string $txt_apellido_materno
 * @property string $txt_rfc
 * @property string $txt_numero_telefonico_nuevo
 * @property string $txt_email
 * @property string $txt_folio_identificacion
 * @property string $fch_nacimiento
 * @property string $num_dias_servicio
 * @property string $txt_token
 * @property string $txt_iccid
 * @property string $txt_imei
 * @property string $txt_numero_referencia
 * @property string $txt_numero_referencia_2
 * @property string $txt_numero_referencia_3
 * @property string $txt_estado
 * @property string $txt_calle_numero
 * @property string $txt_colonia
 * @property string $txt_codigo_postal
 * @property string $txt_municipio
 * @property string $txt_entre_calles
 * @property string $txt_observaciones_punto_referencia
 * @property string $txt_motivo_cancelacion_rechazo
 * @property string $fch_cita
 * @property string $fch_creacion
 * @property string $b_entrega_cat
 * 
 * @property CatAreas $idArea
 * @property CatEquipos $idEquipo
 * @property CatHorarios $idHorario
 * @property CatStatusCitas $idStatus
 * @property CatTiposClientes $idTipoCliente
 * @property CatTiposEntrega $idTipoEntrega
 * @property CatTiposIdentificaciones $idTipoIdentificacion
 * @property CatTiposTramites $idTipoTramite
 * @property EntEnvios $idEnvio
 * @property ModUsuariosEntUsuarios $idUsuario
 */
class EntCitas extends \yii\db\ActiveRecord
{
    public $btnAprobarSupervisor = "<a href='#'  class='btn btn-success js-aprobar'>Aprobar</a>";
    public $btnEditar = "<a href='#'  class='btn btn-primary js-actualizar'>Actualizar</a>";
    public $btnCancelar = "<a href='#'  class='btn btn-danger js-cancelar'>Cancelar</a>";
    public $btnAprobarSupervisorTelcel = "<a href='#'  class='btn btn-success js-aprobar-s-telcel'>Aprobar</a>";
    public $btnAprobarAdministradorTelcel = "<a href='#'  class='btn btn-success js-aprobar-a-telcel'>Aprobar</a>";
    public $isEdicion = "0";

    const TIPO_ENVIO_LOCAL =1;
    const DIAS_ENTREGA_LOCAL = 1;
    const DIAS_ENTREGA_FORANEO = 3;


    public function getConsecutivo()
    {
        $consecutivo = count(EntCitas::find()->where(new Expression('date_format(fch_creacion, "%Y-%m-%d")=date_format(NOW(), "%Y-%m-%d")'))->all());
        $consecutivo++;
        $usuario = EntUsuarios::getUsuarioLogueado();
        $identificador = Constantes::IDENTIFICADOR_CLIENTE . Calendario::getDayNumber(). Calendario::getMonthNumber().Calendario::getYearLastDigit()  . "-" . $consecutivo.$usuario->id_usuario;
        $this->txt_identificador_cliente = $identificador;

    }

    public function statusAprobacionDependiendoUsuario()
    {
       $this->id_status = Permisos::getStatusAprobacionDependiendoUsuario();

    }

    public function statusCancelarDependiendoUsuario()
    {
        $this->id_status = Permisos::getStatusCancelacionesDependiendoUsuario();

    }

    public function statusRechazarDependiendoUsuario()
    {
        $this->id_status = Permisos::getStatusRechazarDependiendoUsuario();

    }

    public function generarNumeroEnvio()
    {
        $apiEnvio = new H2H();
        $respuestaApi = json_decode($apiEnvio->crearEnvio($this));
        if(isset($respuestaApi->NoTracking)){
            $tracking = $respuestaApi->NoTracking;
        }
       
        $envio = new EntEnvios();
        $envio->id_cita = $this->id_cita;
        $envio->txt_token = Utils::generateToken("env_");
        $envio->txt_tracking = $tracking;

        if ($envio->save()) {
            $this->id_envio = $envio->id_envio;
        }


    }

    public function setAutorizadaPor(){
        $usuario = EntUsuarios::getUsuarioLogueado();
        //$this->txt_autorizado_por = $usuario->txt_auth_item;
    }

    public function setAddresCat()
    {
        if ($this->b_entrega_cat && $this->id_cat) {

            $cat = $this->idCat;

            $municipio = RelMunicipioCodigoPostal::find()->where(["txt_codigo_postal" => $cat->txt_codigo_postal])->one();
            if ($municipio) {
                $this->id_municipio = $municipio->id_municipio;
            }

            $this->txt_estado = $cat->txt_estado;
            $this->txt_calle_numero = $cat->txt_calle_numero;
            $this->txt_colonia = $cat->txt_colonia;
            $this->txt_codigo_postal = $cat->txt_codigo_postal;
            $this->txt_municipio = $cat->txt_municipio;
        }


    }

    public function consultarEnvio($tracking)
    {
        $api = new H2H();
        return $api->consultarEnvio($tracking);
    }

    public function consultarHistorico($tracking)
    {
        $api = new H2H();
        return $api->consultarHistorico($tracking);
    }

    public function guardarHistorialUpdate()
    {
        $usuario = EntUsuarios::getUsuarioLogueado();
        
        $message = "Cita editada por " . $usuario->txtAuthItem->description.". Fecha promesa: ".Calendario::getDateComplete($this->fch_cita);
        $message2 = "Cita editada por " . $usuario->txtAuthItem->description;
        EntHistorialCambiosCitas::guardarHistorial($this->id_cita, $message);

        $this->txt_autorizado_por = $message2;
        $this->save();
    }

    public function guardarHistorialDependiendoUsuario($new = false, $cancel = false, $rechazar=false)
    {
        if($new){
            $message = Permisos::getMessageHistorialGuardar();
        }else{
            $message = Permisos::getMessageHistorialAprobar();
        }

        if($cancel){
            $message = Permisos::getMessageHistorialCancelar();
        }

        if($rechazar){
            $message = Permisos::getMessageHistorialRechazar();
        }

        EntHistorialCambiosCitas::guardarHistorial($this->id_cita, $message.". Fecha promesa: ".Calendario::getDateComplete($this->fch_cita));
        $this->txt_autorizado_por = $message;
        $this->save();
    }

    public function iniciarModelo($idArea = null, $numServicios = null, $tipoEntrega = null)
    {
        $usuario = EntUsuarios::getUsuarioLogueado();
        if (\Yii::$app->user->can(Constantes::USUARIO_SUPERVISOR)) {
            $this->id_status = Constantes::STATUS_AUTORIZADA_POR_SUPERVISOR;
        } else {
            $this->id_status = Constantes::STATUS_CREADA;
        }

        //$this->id_area = $idArea;
        //$this->num_dias_servicio = $numServicios;
        $this->id_tipo_entrega = $tipoEntrega;
        $this->id_usuario = $usuario->id_usuario;;
        $this->txt_token = Utils::generateToken("cit_");
        $this->id_equipo = 1;

        if($usuario->id_call_center){
            $this->id_call_center = $usuario->id_call_center;
        }

        if (YII_ENV_DEV) {
            $this->txt_telefono = "1234567890";
            $this->txt_nombre = "John";
            $this->txt_apellido_paterno = "Doe";
            $this->fch_nacimiento = "16-07-1990";
            $this->txt_email = "john-doe@2gom.com.mx";
            $this->id_tipo_tramite = 1;
            $this->id_tipo_cliente = 1;
            $this->txt_codigo_postal = "54710";
            $this->txt_calle_numero = "Av. Lomas Verdes #480 201-D";
            $this->txt_colonia = "Los Álamos";
            $this->txt_municipio = "Naucalpan de Júarez";
            $this->txt_estado = "Estado de México";
            $this->txt_entre_calles = "Av. Lomas Verdes y prolongación Alamos";
            $this->txt_observaciones_punto_referencia = "A lado de un oxxo";
            $this->txt_numero_referencia = "5565501987";
            $this->id_tipo_identificacion = 1;
            $this->txt_folio_identificacion = "12345678";
        }

    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_citas';
    }

    public function validateTel($attribute, $params, $validator)
    {

        // $telefonoDisponible = EntCitas::find()
        //     ->where(['txt_telefono' => $this->txt_telefono])
        //     ->andWhere(['in', 'id_status', [
        //         Constantes::STATUS_CREADA,
        //         Constantes::STATUS_AUTORIZADA_POR_SUPERVISOR,
        //         Constantes::STATUS_AUTORIZADA_POR_SUPERVISOR_TELCEL,
        //         Constantes::STATUS_AUTORIZADA_POR_ADMINISTRADOR_CC,
        //         // Constantes::STATUS_CANCELADA_SUPERVISOR_CC,
        //         // Constantes::STATUS_CANCELADA_SUPERVISOR_TELCEL,
        //         // Constantes::STATUS_CANCELADA_ADMINISTRADOR_CC,
        //         // Constantes::STATUS_CANCELADA_ADMINISTRADOR_TELCEL,
        //         Constantes::STATUS_AUTORIZADA_POR_ADMINISTRADOR_TELCEL,
        //         Constantes::STATUS_AUTORIZADA_POR_MASTER_BRIGHT_STAR,
        //         Constantes::STATUS_AUTORIZADA_POR_MASTER_TELCEL,
        //         Constantes::STATUS_AUTORIZADA_POR_MASTER_CALL_CENTER,
        //         // Constantes::STATUS_CANCELADA_POR_MASTER_BRIGHT_STAR,
        //         // Constantes::STATUS_CANCELADA_POR_MASTER_TELCEL,
        //         // Constantes::STATUS_CANCELADAS_POR_MASTER_CALL_CENTER,
        //         Constantes::STATUS_RECIBIDO_MENSAJERIA,
        //         Constantes::STATUS_LISTO_ENTREGA,
        //         Constantes::STATUS_ANOMALO,
        //         // Constantes::STATUS_ENTREGADO,
        //         // Constantes::STATUS_CANCELADO,
        //         // Constantes::STATUS_NO_ENTREGADO,
        //         Constantes::STATUS_NO_VISITADO,
        //         Constantes::STATUS_PRIMERA_VISITA,
        //         Constantes::STATUS_SEGUNDA_VISITA,
        //     ]])
        //     ->all();

        // if ($telefonoDisponible) {
        //     $this->addError($attribute, 'El número teléfonico ya se encuentra en una cita activa');
        // }


    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['b_documentos'], 'required', 'on' => ['autorizar', 'autorizar-update'],
                'when' => function ($model) {
                    return $model->id_equipo == Constantes::SIN_EQUIPO;
                }, 'whenClient' => "function (attribute, value) {
                    
                    return $('#entcitas-id_equipo').val()=='" . Constantes::SIN_EQUIPO . "';
                }"
            ],
            
          
            [
                ['txt_sap_promocional'], 'required', 'on' => ['autorizar', 'autorizar-update'],
                'when' => function ($model) {
                    return $model->txt_promocional;
                }, 'whenClient' => "function (attribute, value) {
                    
                    return $('#entcitas-txt_promocional').val();
                }"
            ],
            [
                ['txt_sap_promocional_2'], 'required', 'on' => ['autorizar', 'autorizar-update'],
                'when' => function ($model) {
                    return $model->txt_promocional_2;
                }, 'whenClient' => "function (attribute, value) {
                    
                    return $('#entcitas-txt_promocional_2').val();
                }"
            ],
            [
                ['txt_sap_promocional_3'], 'required', 'on' => ['autorizar', 'autorizar-update'],
                'when' => function ($model) {
                    return $model->txt_promocional_3;
                }, 'whenClient' => "function (attribute, value) {
                    
                    return $('#entcitas-txt_promocional_3').val();
                }"
            ],
            [
                ['txt_sap_promocional_4'], 'required', 'on' => ['autorizar', 'autorizar-update'],
                'when' => function ($model) {
                    return $model->txt_promocional_4;
                }, 'whenClient' => "function (attribute, value) {
                    
                    return $('#entcitas-txt_promocional_4').val();
                }"
            ],
            [["txt_sap_equipo", "txt_sap_iccid"], 'required', 'on' => ['autorizar', 'autorizar-update']],
            [
                ['txt_sap_promocional_5'], 'required', 'on' => ['autorizar', 'autorizar-update'],
                'when' => function ($model) {
                    return $model->txt_promocional_5;
                }, 'whenClient' => "function (attribute, value) {
                    
                    return $('#entcitas-txt_promocional_5').val();
                }"
            ],
            [
                ['id_horario'], 'required', 'on' => ['autorizar', 'autorizar-update'],
                'when' => function ($model) {
                    return $model->b_entrega_cat==0;
                }, 'whenClient' => "function (attribute, value) {
                    
                    return !$('#entcitas-b_entrega_cat').prop('checked');
                }"
            ],
            [
                ['txt_horario_entrega_cat'], 'required', 'on' => ['autorizar', 'autorizar-update'],
                'when' => function ($model) {
                    return $model->b_entrega_cat==1;
                }, 'whenClient' => "function (attribute, value) {
                    console.log(attribute);
                    console.log(value);
                    return $('#entcitas-b_entrega_cat').prop('checked');
                }"
            ],
            
            
            [
                ['id_cat'], 'required',
                'when' => function ($model) {
                    return $model->b_entrega_cat == 1;
                }, 'whenClient' => "function (attribute, value) {
                    
                    return $('#entcitas-b_entrega_cat').prop('checked');
                }"
            ],

            [
                ["txt_telefono"], 'validateTel', 'on' => ['autorizar', 'create-call-center']
            ],
            [
                [
                    'id_tipo_tramite',
                    //'id_equipo',
                    'id_area',
                    'id_tipo_entrega',
                    'id_usuario',
                    'id_status',
                    'id_envio',
                    'id_tipo_cliente',
                    'id_tipo_identificacion',
                    'id_horario',
                    'b_documentos',
                    'b_promocionales',
                    'b_sim',
                    'b_entrega_cat',
                    'id_cat'
                ],
                'integer'
            ],
            [[
                'id_usuario', 'id_status', 'txt_telefono', 'txt_email', 'txt_nombre', 'txt_apellido_paterno',
                'txt_email',
                'txt_equipo',
                //'fch_nacimiento',
                'num_dias_servicio',
                'txt_estado',
                'txt_calle_numero',
                'txt_colonia',
                'txt_codigo_postal',
                'txt_municipio',
                'fch_cita',
                'txt_numero_referencia',
                'txt_token', 'id_tipo_tramite', 'id_equipo', 'id_area', 'id_tipo_entrega', 'id_usuario', 'id_status', 'id_tipo_cliente', 'id_tipo_identificacion', 
            ], 'required'],
            [['id_tipo_cancelacion'], 'exist', 'skipOnError' => true, 'targetClass' => CatTiposCancelacion::className(), 'targetAttribute' => ['id_tipo_cancelacion' => 'id_tipo_cancelacion']],
            [['txt_motivo_cancelacion_rechazo'], 'required', 'on' => 'cancelar'],
            [['txt_motivo_rechazo'], 'required', 'on' => 'rechazo'],
            [['txt_telefono', 'txt_numero_referencia'], 'string', 'max' => 10, 'min' => 10, 'tooLong' => 'El campo no debe superar 10 dígitos', 'tooShort' => 'El campo debe ser mínimo de 10 digítos'],
            [['txt_email'], 'email'],
            [['txt_tpv'], 'trim'],
            [['fch_nacimiento', 'fch_cita', 'fch_creacion'], 'safe'],
            [['txt_rfc', 'txt_estado'], 'string', 'max' => 20],
            [['txt_telefono', 'txt_numero_referencia', 'txt_numero_referencia_2', 'txt_numero_referencia_3'], 'string', 'max' => 10],
            [['txt_nombre', 'txt_apellido_paterno', 'txt_apellido_materno', 'txt_folio_identificacion'], 'string', 'max' => 200],
            [['txt_numero_telefonico_nuevo'], 'string', 'max' => 10],
            [['txt_email', 'txt_colonia', 'txt_municipio'], 'string', 'max' => 100],
            [['num_dias_servicio', 'isEdicion', 'txt_horario_entrega_cat'], 'string', 'max' => 50],
            [['txt_token', 'txt_identificador_cliente'], 'string', 'max' => 60],
            [['txt_iccid'], 'string', 'max' => 19],
            [['txt_imei'], 'string', 'max' => 15],
            [['txt_calle_numero'], 'string', 'max' => 150],
            [['txt_codigo_postal'], 'string', 'max' => 5],
            [['txt_entre_calles', 'txt_observaciones_punto_referencia'], 'string', 'max' => 500],
            [['txt_motivo_cancelacion_rechazo',"txt_motivo_rechazo", 'txt_promocional', 'txt_promocional_2', 'txt_promocional_3','txt_promocional_4','txt_promocional_5'], 'string', 'max' => 700],
            [['txt_sap_promocional', 'txt_sap_promocional_2', 'txt_sap_promocional_3','txt_sap_promocional_4','txt_sap_promocional_5', "txt_sap_equipo", "txt_sap_iccid"], 'string', 'max' => 50],
            [['txt_token'], 'unique'],
            [['id_area'], 'exist', 'skipOnError' => true, 'targetClass' => CatAreas::className(), 'targetAttribute' => ['id_area' => 'id_area']],
            [['id_equipo'], 'exist', 'skipOnError' => true, 'targetClass' => CatEquipos::className(), 'targetAttribute' => ['id_equipo' => 'id_equipo']],
            [['id_horario'], 'exist', 'skipOnError' => true, 'targetClass' => EntHorariosAreas::className(), 'targetAttribute' => ['id_horario' => 'id_horario_area']],
            [['id_status'], 'exist', 'skipOnError' => true, 'targetClass' => CatStatusCitas::className(), 'targetAttribute' => ['id_status' => 'id_statu_cita']],
            [['id_tipo_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => CatTiposClientes::className(), 'targetAttribute' => ['id_tipo_cliente' => 'id_tipo_cliente']],
            [['id_tipo_entrega'], 'exist', 'skipOnError' => true, 'targetClass' => CatTiposEntrega::className(), 'targetAttribute' => ['id_tipo_entrega' => 'id_tipo_entrega']],
            [['id_tipo_identificacion'], 'exist', 'skipOnError' => true, 'targetClass' => CatTiposIdentificaciones::className(), 'targetAttribute' => ['id_tipo_identificacion' => 'id_tipo_identificacion']],
            [['id_tipo_tramite'], 'exist', 'skipOnError' => true, 'targetClass' => CatTiposTramites::className(), 'targetAttribute' => ['id_tipo_tramite' => 'id_tramite']],
            [['id_envio'], 'exist', 'skipOnError' => true, 'targetClass' => EntEnvios::className(), 'targetAttribute' => ['id_envio' => 'id_envio']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => EntUsuarios::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_cita' => 'Id Cita',
            'id_tipo_tramite' => 'Tipo de trámite',
            'id_equipo' => 'Equipo',
            'id_area' => 'Área',
            'id_tipo_entrega' => 'Tipo de entrega',
            'id_usuario' => 'Usuario',
            'id_status' => 'Estatus de la cita',
            'id_envio' => 'Id. del envío',
            'id_tipo_cliente' => 'Tipo de cliente',
            'id_tipo_identificacion' => 'Tipo de identificación',
            'id_horario' => 'Horario',
            'txt_horario_entrega_cat' => 'Horario',
            'txt_telefono' => 'Teléfono',
            'txt_nombre' => 'Nombre',
            'txt_apellido_paterno' => 'Apellido paterno',
            'txt_apellido_materno' => 'Apellido materno',
            'txt_rfc' => 'RFC',
            'txt_numero_telefonico_nuevo' => 'Número teléfonico provicional/nuevo',
            'txt_email' => 'Correo electrónico',
            'txt_folio_identificacion' => 'Folio de identificación',
            'fch_nacimiento' => 'Fecha de nacimiento',
            'num_dias_servicio' => 'Días de servicio',
            'txt_token' => 'Token',
            'txt_iccid' => 'Iccid',
            'txt_imei' => 'IMEI',
            'txt_numero_referencia' => 'Número teléfonico de referencia',
            'txt_numero_referencia_2' => 'Número teléfonico de referencia 2',
            'txt_numero_referencia_3' => 'Número teléfonico de referencia 3',
            'txt_estado' => 'Estado',
            'txt_calle_numero' => 'Calle y número',
            'txt_colonia' => 'Colonia',
            'txt_codigo_postal' => 'Codigo postal',
            'txt_municipio' => 'Municipio / Delegación',
            'txt_entre_calles' => 'Entre calles',
            'txt_observaciones_punto_referencia' => 'Puntos de referencia',
            'txt_motivo_cancelacion_rechazo' => 'Motivo cancelación',
            'fch_cita' => 'Fecha de la cita',
            'fch_creacion' => 'Fecha creación',
            'txt_tpv' => 'TPV',
            'b_documentos' => 'Solo documentos',
            'b_promocionales' => 'Con promocionales',
            'b_sim' => 'Con sim',
            'txt_identificador_cliente' => 'Consecutivo',
            'id_tipo_cancelacion' => "",
            'isEdicion' => "Edicion",
            'txt_promocional' => "Promocionales",
            'b_entrega_cat' => "Entrega en CAC",
            'id_cat' => "CAC",
            'txtTracking' => "Id. envio",
            'txt_equipo' => "Equipo",
            'txt_promocional'=>"Promocional #1",
            'txt_sap_promocional'=>"Clave SAP promocional #1",

            'txt_promocional_2'=>"Promocional #2",
            'txt_sap_promocional_2'=>"Clave SAP promocional #2",

            'txt_promocional_3'=>"Promocional #3",
            'txt_sap_promocional_3'=>"Clave SAP promocional #3",

            'txt_promocional_4'=>"Promocional #4",
            'txt_sap_promocional_4'=>"Clave SAP promocional #4",

            'txt_promocional_5'=>"Promocional #5",
            'txt_sap_promocional_5'=>"Clave SAP promocional #5",
            'txt_sap_equipo'=>"SAP equipo",
            'txt_sap_iccid'=>"SAP ICCID",
            "txt_motivo_rechazo"=>"Motivo de rechazo"
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdArea()
    {
        return $this->hasOne(CatAreas::className(), ['id_area' => 'id_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdEquipo()
    {
        return $this->hasOne(CatEquipos::className(), ['id_equipo' => 'id_equipo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdHorario()
    {
        return $this->hasOne(EntHorariosAreas::className(), ['id_horario_area' => 'id_horario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdStatus()
    {
        return $this->hasOne(CatStatusCitas::className(), ['id_statu_cita' => 'id_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoCliente()
    {
        return $this->hasOne(CatTiposClientes::className(), ['id_tipo_cliente' => 'id_tipo_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoEntrega()
    {
        return $this->hasOne(CatTiposEntrega::className(), ['id_tipo_entrega' => 'id_tipo_entrega']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMunicipio()
    {
        return $this->hasOne(CatMunicipios::className(), ['id_municipio' => 'id_municipio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoIdentificacion()
    {
        return $this->hasOne(CatTiposIdentificaciones::className(), ['id_tipo_identificacion' => 'id_tipo_identificacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoTramite()
    {
        return $this->hasOne(CatTiposTramites::className(), ['id_tramite' => 'id_tipo_tramite']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdEnvio()
    {
        return $this->hasOne(EntEnvios::className(), ['id_envio' => 'id_envio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsuario()
    {
        return $this->hasOne(EntUsuarios::className(), ['id_usuario' => 'id_usuario']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCallCenter()
    {
        return $this->hasOne(CatCallsCenters::className(), ['id_call_center' => 'id_call_center']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntHistorialCambiosCitas()
    {
        return $this->hasMany(EntHistorialCambiosCitas::className(), ['id_cita' => 'id_cita'])->orderBy('fch_modificacion DESC');
    }

    public static function validarDiaEntrega($fecha)
    {

        $dia = Calendario::getDayName($fecha);

        if ($dia == "Domingo") {
            $tiempo = strtotime($fecha . "+1 day");
            $fecha = date('d-m-Y', $tiempo);
            $fecha = self::validarDiaEntrega($fecha);
        }


        return $fecha;


    }

    public static function getFechaEntrega($fecha)
    {
        $tiempo = strtotime($fecha . "+4 day");
        $fecha = date('d-m-Y', $tiempo);

        return self::validarDiaEntrega($fecha);
    }

    public static function getColorStatus($idStatus)
    {

        switch ($idStatus) {
            case Constantes::STATUS_CREADA:
                $statusColor = Constantes::COLOR_STATUS_CREADA;
                break;
            case Constantes::STATUS_AUTORIZADA_POR_SUPERVISOR:
                $statusColor = Constantes::COLOR_STATUS_AUTORIZADA_POR_SUPERVISOR;
                break;
            case Constantes::STATUS_AUTORIZADA_POR_ADMINISTRADOR_CC:
                $statusColor = Constantes::COLOR_STATUS_AUTORIZADA_POR_SUPERVISOR;
                break;
            case Constantes::STATUS_AUTORIZADA_POR_SUPERVISOR_TELCEL:
                $statusColor = Constantes::COLOR_STATUS_AUTORIZADA_POR_SUPERVISOR_TELCEL;
                break;
            case Constantes::STATUS_AUTORIZADA_POR_ADMINISTRADOR_TELCEL:
                $statusColor = Constantes::COLOR_STATUS_AUTORIZADA_POR_ADMINISTRADOR_TELCEL;
                break;
            case Constantes::STATUS_CANCELADA_SUPERVISOR_CC:
                $statusColor = Constantes::COLOR_STATUS_CANCELADA;
                break;
            case Constantes::STATUS_CANCELADA_ADMINISTRADOR_CC:
                $statusColor = Constantes::COLOR_STATUS_CANCELADA;
                break;
            case Constantes::STATUS_CANCELADA_SUPERVISOR_TELCEL:
                $statusColor = Constantes::COLOR_STATUS_CANCELADA;
                break;
            case Constantes::STATUS_CANCELADA_ADMINISTRADOR_TELCEL:
                $statusColor = Constantes::COLOR_STATUS_CANCELADA;
                break;

            default:
                $statusColor = Constantes::COLOR_STATUS_AUTORIZADA_POR_ADMINISTRADOR_TELCEL;
                break;
        }

        return $statusColor;
    }

    public function getBotonesSupervisor()
    {
        $botones = new BotonesCitas();


        return $contenedor = $botones->getBotones($this);

    }

    public function getBotonGuardar()
    {
        if ($this->isNewRecord) {
            return Html::submitButton("<span class='ladda-label'> <i class='site-menu-icon pe-headphones' aria-hidden='true'></i> " . ($this->isNewRecord ? 'Generar cita' : 'Actualizar cita') . "</span>", ['id'=>"js-guardar-cita-btn", 'class' => ($this->isNewRecord ? 'btn btn-success btn-form-save' : 'btn btn-primary ') . "  float-right ladda-button", "data-style" => "zoom-in"]);
        }
        return "";
    }

    public function validarEdicionCita()
    {
        if ((Utils::getHorasEdicion($this->fch_creacion) < Constantes::TIEMPO_EDICION) && $this->validarEdicionCitaStatus()) {
            return true;
        }
        return false;

    }

    public function validarEdicionCitaStatus()
    {
        $usuario = EntUsuarios::getUsuarioLogueado();
        // si el usuario es call-center y la cita sigue en crear podra editar la cita
        if ($usuario->txt_auth_item == Constantes::USUARIO_CALL_CENTER && Constantes::STATUS_CREADA == $this->id_status) {
            return true;
        }

        if ($usuario->txt_auth_item == Constantes::USUARIO_SUPERVISOR
            && (Constantes::STATUS_CREADA == $this->id_status)) {
            return true;
        }

        if ($usuario->txt_auth_item == Constantes::USUARIO_ADMINISTRADOR_CC) {
            return true;
        }

        return false;
    }

    /** 
     * @return \yii\db\ActiveQuery 
     */
    public function getIdTipoCancelacion()
    {
        return $this->hasOne(CatTiposCancelacion::className(), ['id_tipo_cancelacion' => 'id_tipo_cancelacion']);
    }

    /** 
     * @return \yii\db\ActiveQuery 
     */
    public function getIdCat()
    {
        return $this->hasOne(CatCats::className(), ['id_cat' => 'id_cat']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntEnvios()
    {
        return $this->hasOne(EntEnvios::className(), ['id_cita' => 'id_cita']);
    }

    public function getNombreCompleto()
    {
        return $this->txt_nombre . " " . $this->txt_apellido_paterno;
    }

    /* Getter for country name */
    public function getTxtTracking()
    {
        return $this->entEnvios->txt_tracking;
    }

    public function getPromocionalesData(){
        $promocionales = "";
        if($this->txt_promocional){
            $promocionales .= $this->txt_sap_promocional." - ".$this->txt_promocional.",";
        }

        if($this->txt_promocional_2){
            $promocionales .= $this->txt_sap_promocional_2." - ".$this->txt_promocional_2.",";
        }

        if($this->txt_promocional_3){
            $promocionales .= $this->txt_sap_promocional_3." - ".$this->txt_promocional_3.",";
        }

        if($this->txt_promocional_4){
            $promocionales .= $this->txt_sap_promocional_4." - ".$this->txt_promocional_4.",";
        }

        if($this->txt_promocional_5){
            $promocionales .= $this->txt_sap_promocional_5." - ".$this->txt_promocional_5.",";
        }

        return $promocionales;
    }

    public function getPromocional1(){
        return $this->txt_sap_promocional." - ".$this->txt_promocional;
    }

    public function getPromocional2(){
        return $this->txt_sap_promocional_2." - ".$this->txt_promocional_2;
    }

    public function getPromocional3(){
        return $this->txt_sap_promocional_3." - ".$this->txt_promocional_3;
    }

    public function getPromocional4(){
        return $this->txt_sap_promocional_4." - ".$this->txt_promocional_4;
    }

    public function getPromocional5(){
        return $this->txt_sap_promocional_5." - ".$this->txt_promocional_5;
    }

    public function getPathBaseEvidencia(){
        $pathBase = "evidencias/";
        $mesCambio = Calendario::getMonthNumber($this->fch_cita);
        if($mesCambio>=8){
            $pathBase = "evidencias2/";
        }
       
 
        $anio = Calendario::getYearLastDigit($this->fch_cita);
        $pathAnio = $pathBase.$anio."/";
        Files::validarDirectorio($pathAnio);
        $mes = Calendario::getMonthNumber($this->fch_cita);
        $pathMes = $pathAnio.$mes."/";
        Files::validarDirectorio($pathMes);

        return $pathMes;

    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Place your custom code here
            $this->validarDiaEntregaEnviador();
            return true;
        } else {
            return false;
        }
    }

    public function validarDiaEntregaEnviador(){
       

        $tipoEnvio = $this->idMunicipio ? $this->idMunicipio->id_tipo:null;
        $diasAntes = self::DIAS_ENTREGA_FORANEO;
        if($tipoEnvio == self::TIPO_ENVIO_LOCAL){
            $diasAntes = self::DIAS_ENTREGA_LOCAL;
        }

        $dia = Calendario::getNumeroDiaSemana($this->fch_cita);

        if ($dia <=$diasAntes) {
            $diasAntes = $diasAntes + 2;
        }


        $tiempo = strtotime($this->fch_cita . "-".$diasAntes." day");
        $this->fch_entrega_equipo = date('Y-m-d H:i:s', $tiempo);

        return $this->fch_entrega_equipo;
    }


}
