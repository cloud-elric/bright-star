<?php

namespace app\models;

use Yii;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\modules\ModUsuarios\models\Utils;
use yii\helpers\Html;

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
    public $btnRechazar = "<a href='#'  class='btn btn-warning js-rechazar'>Rechazar</a>";
    public $btnCancelar = "<a href='#'  class='btn btn-danger js-cancelar'>Cancelar</a>";
    // Constructor
    

    public function iniciarModelo($status=null, $idArea=null, $numServicios=null, $tipoEntrega=null){

        $this->id_status = $status;
        $this->id_area = $idArea;
        $this->num_dias_servicio = $numServicios;
        $this->id_tipo_entrega = $tipoEntrega;
        $this->id_usuario = EntUsuarios::getUsuarioLogueado()->id_usuario;;
        $this->txt_token = Utils::generateToken("cit_");

        if(YII_ENV_DEV){
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tipo_tramite', 'id_equipo', 'id_area', 'id_tipo_entrega', 'id_usuario', 'id_status', 'id_envio', 'id_tipo_cliente', 'id_tipo_identificacion', 'id_horario'], 'integer'],
            [['id_usuario', 'id_status', 'txt_telefono', 'txt_email','txt_nombre', 'txt_apellido_paterno','txt_folio_identificacion',
            'txt_email',
            'fch_nacimiento',
            'num_dias_servicio',
            'txt_estado',
            'txt_calle_numero',
            'txt_colonia',
            'txt_codigo_postal',
            'txt_municipio',
            'fch_cita',
            'txt_numero_referencia',
            'txt_token','id_tipo_tramite', 'id_equipo', 'id_area', 'id_tipo_entrega', 'id_usuario', 'id_status',  'id_tipo_cliente', 'id_tipo_identificacion', 'id_horario'], 'required'],
            [['txt_telefono', 'txt_numero_referencia'], 'string', 'max' => 10, 'min' => 10, 'tooLong' => 'El campo no debe superar 10 dígitos','tooShort' => 'El campo debe ser mínimo de 10 digítos'],
            [['txt_email'], 'email'],
            [['fch_nacimiento', 'fch_cita', 'fch_creacion'], 'safe'],
            [['txt_telefono', 'txt_rfc', 'txt_numero_referencia', 'txt_numero_referencia_2', 'txt_numero_referencia_3', 'txt_estado'], 'string', 'max' => 20],
            [['txt_nombre', 'txt_apellido_paterno', 'txt_apellido_materno', 'txt_folio_identificacion'], 'string', 'max' => 200],
            [['txt_numero_telefonico_nuevo'], 'string', 'max' => 10],
            [['txt_email', 'txt_colonia', 'txt_municipio'], 'string', 'max' => 100],
            [['num_dias_servicio'], 'string', 'max' => 50],
            [['txt_token'], 'string', 'max' => 60],
            [['txt_iccid', 'txt_imei', 'txt_calle_numero'], 'string', 'max' => 150],
            [['txt_codigo_postal'], 'string', 'max' => 5],
            [['txt_entre_calles', 'txt_observaciones_punto_referencia'], 'string', 'max' => 500],
            [['txt_motivo_cancelacion_rechazo'], 'string', 'max' => 700],
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
            'id_envio' => 'Identificador del envio',
            'id_tipo_cliente' => 'Tipo de cliente',
            'id_tipo_identificacion' => 'Tipo de identificación',
            'id_horario' => 'Horario',
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
            'txt_municipio' => 'Municipio',
            'txt_entre_calles' => 'Entre calles',
            'txt_observaciones_punto_referencia' => 'Puntos de referencia',
            'txt_motivo_cancelacion_rechazo' => 'Motivo cancelación o rechazo',
            'fch_cita' => 'Fecha de la cita',
            'fch_creacion' => 'Fecha creación',
            'txt_tpv'=>'TPV',
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
        return $this->hasOne(CatHorarios::className(), ['id_horario' => 'id_horario']);
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

    public static function getFechaEntrega($fecha){
        $tiempo = strtotime($fecha. "+2 day");
        $fecha = date('d-m-Y', $tiempo);

        return self::validarDiaEntrega($fecha);
    }

    public static function getColorStatus($idStatus){
        switch ($idStatus) {
            case '1':
                $statusColor = Constantes::COLOR_STATUS_CREADA;
                break;
            case '2':
                $statusColor = Constantes::COLOR_STATUS_AUTORIZADA_POR_SUPERVISOR;
                break;
            case '3':
                $statusColor = Constantes::COLOR_STATUS_AUTORIZADA_POR_SUPERVISOR_TELCEL;
                break;    
            case '4':
                $statusColor = Constantes::COLOR_STATUS_RECHAZADA;
                break;
            case '5':
                $statusColor = Constantes::COLOR_STATUS_CANCELADA;
            break;  
            case '7':
                $statusColor = Constantes::COLOR_STATUS_AUTORIZADA_POR_ADMINISTRADOR_TELCEL;
            break;  
                  
            default:
                # code...
                break;
        }

        return $statusColor;
    }

    public function getBotonesSupervisor(){

        if(\Yii::$app->user->can(Constantes::USUARIO_SUPERVISOR) && Constantes::STATUS_CREADA==$this->id_status){
            $botones = $this->btnAprobarSupervisor.$this->btnCancelar.$this->btnRechazar;
            $contenedor = "<div class='pt-15 example-buttons text-right'>".$botones."</div>";
           return $contenedor;
        }

        return "";
    }

    public function getBotonGuardar(){
        if($this->validarEdicionCita()){
            return Html::submitButton("<span class='ladda-label'>".($this->isNewRecord ? 'Generar cita' : 'Actualizar cita')."</span>", ['class' => ($this->isNewRecord ? 'btn btn-success' : 'btn btn-primary ')."  float-right ladda-button", "data-style"=>"zoom-in"]);
        }
        
        return "";
    }

    public function validarEdicionCita(){
        if((Utils::getHorasEdicion($this->fch_creacion) < Constantes::TIEMPO_EDICION ) && $this->validarEdicionCitaStatus()){
            return true;
        }
        return false;
       
    }

    public function validarEdicionCitaStatus(){

        // si el usuario es call-center y la cita sigue en crear podra editar la cita
        if(\Yii::$app->user->can(Constantes::USUARIO_CALL_CENTER) && Constantes::STATUS_CREADA==$this->id_status){
                return true;
        }

        if(\Yii::$app->user->can(Constantes::USUARIO_SUPERVISOR) 
            && (Constantes::STATUS_CREADA==$this->id_status)){
                return true;
        }

        return false;
    }

    
}
